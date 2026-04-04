<?php

declare(strict_types=1);

namespace App\CreditWallet\Application\Command\ExecuteCreditGrant;

use App\CreditWallet\Domain\Enum\GrantTargetType;
use App\CreditWallet\Domain\Enum\TransactionType;
use App\CreditWallet\Infrastructure\Doctrine\Entity\AdminCreditGrant;
use App\CreditWallet\Infrastructure\Doctrine\Entity\CreditWallet;
use App\CreditWallet\Infrastructure\Doctrine\Repository\DoctrineAdminCreditGrantRepository;
use App\Notification\Domain\Enum\NotificationTypeEnum;
use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use App\Shared\Application\Command\AsCommandHandler;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommandHandler]
final readonly class ExecuteCreditGrantCommandHandler
{
    public function __construct(
        private DoctrineAdminCreditGrantRepository $grantRepository,
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(ExecuteCreditGrantCommand $command): void
    {
        /** @var ?AdminCreditGrant $grant */
        $grant = $this->grantRepository->findOneBy(['uuid' => $command->grantUuid]);

        if (null === $grant || $grant->isExecuted()) {
            return;
        }

        $users = $this->resolveTargetUsers($grant);
        $transactionType = $grant->isDeduction()
            ? TransactionType::ADMIN_DEDUCTION
            : TransactionType::ADMIN_GRANT;

        foreach ($users as $user) {
            $wallet = $this->em->getRepository(CreditWallet::class)->findOneBy(['user' => $user]);
            if (null === $wallet) {
                $wallet = (new CreditWallet())->setUser($user)->setCredit(0);
                $this->em->persist($wallet);
            }

            $amount = $grant->getAmount();

            // Pour une déduction : on clamp au solde disponible (pas de crédit négatif)
            if ($grant->isDeduction()) {
                $amount = min($amount, $wallet->getCredit() ?? 0);
                if ($amount <= 0) {
                    continue;
                }
            }

            $wallet->makeTransaction($transactionType, $amount);
            $this->createNotification($grant, $user, $amount);
        }

        $grant->setExecutedAt(new \DateTimeImmutable());
        $this->em->flush();
    }

    /**
     * @return UserVisitor[]
     */
    private function resolveTargetUsers(AdminCreditGrant $grant): array
    {
        return match ($grant->getTargetType()) {
            GrantTargetType::ALL => $this->em->createQuery('SELECT u FROM ' . UserVisitor::class . ' u')->getResult(),
            GrantTargetType::PLAYER => $grant->getTargetPlayer() ? [$grant->getTargetPlayer()] : [],
            GrantTargetType::CHAMPIONSHIP => $this->resolveChampionshipUsers($grant),
        };
    }

    /**
     * @return UserVisitor[]
     */
    private function resolveChampionshipUsers(AdminCreditGrant $grant): array
    {
        $championship = $grant->getTargetChampionship();
        if (null === $championship) {
            return [];
        }

        $excludedIds = $grant->getExcludedPlayers()
            ->map(fn (UserVisitor $u) => $u->getId())
            ->toArray();

        $players = $this->em->getRepository(Player::class)->findBy(['championship' => $championship]);

        $users = [];
        foreach ($players as $player) {
            $user = $player->getUser();
            if (!in_array($user->getId(), $excludedIds, true)) {
                $users[] = $user;
            }
        }

        return $users;
    }

    private function createNotification(AdminCreditGrant $grant, UserVisitor $user, int $amount): void
    {
        $isDeduction = $grant->isDeduction();
        $title = $isDeduction
            ? sprintf('%d crédits déduits de ton compte', $amount)
            : sprintf('Tu as reçu %d crédits !', $amount);

        $body = sprintf(
            '<p>%s</p><p><strong>Motif :</strong> %s</p>',
            $isDeduction
                ? sprintf('%d crédits ont été retirés de ton portefeuille.', $amount)
                : sprintf('%d crédits ont été ajoutés à ton portefeuille.', $amount),
            htmlspecialchars($grant->getReason(), ENT_QUOTES)
        );

        $notification = (new Notification())
            ->setTitle($title)
            ->setBody($body)
            ->setType(NotificationTypeEnum::SYSTEM_CREDIT)
            ->setPublishedAt(new \DateTimeImmutable())
        ;

        // Cibler uniquement ce joueur
        $notification->addTarget($user);

        $this->em->persist($notification);
    }
}
