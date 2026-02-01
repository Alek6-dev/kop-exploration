<?php

declare(strict_types=1);

namespace App\Cosmetic\Application\Command\BuyCosmetic;

use App\Cosmetic\Domain\Exception\CosmeticException;
use App\Cosmetic\Domain\Model\CosmeticPossessedInterface;
use App\Cosmetic\Domain\Repository\CosmeticPossessedRepositoryInterface;
use App\Cosmetic\Infrastructure\Doctrine\Entity\CosmeticPossessed;
use App\CreditWallet\Application\Command\MakeTransaction\MakeTransactionCommand;
use App\CreditWallet\Domain\Enum\TransactionType;
use App\Shared\Application\Command\AsCommandHandler;
use App\Shared\Application\Command\CommandBusInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[AsCommandHandler]
final readonly class BuyCosmeticCommandHandler
{
    public function __construct(
        private CosmeticPossessedRepositoryInterface $cosmeticPossessedRepository,
        private Security $security,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(BuyCosmeticCommand $command): CosmeticPossessedInterface
    {
        $cosmeticPossessed = $this->cosmeticPossessedRepository
            ->withCosmetic($command->cosmetic)
            ->withUser($command->user)
            ->first()
        ;

        if ($cosmeticPossessed) {
            throw CosmeticException::alreadyPossessed($command->user->getUuid(), $command->cosmetic->getUuid());
        }

        $cosmeticPossessed = (new CosmeticPossessed())
            ->setUser($command->user)
            ->setCosmetic($command->cosmetic)
            ->setPrice($command->cosmetic->getPrice())
        ;

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();

        $this->commandBus->dispatch(new MakeTransactionCommand(
            $user->getCreditWallet()->getUuid(),
            TransactionType::CONSUME_COSMETIC,
            $command->cosmetic->getPrice(),
        ));

        $this->cosmeticPossessedRepository->add($cosmeticPossessed);

        // TODO: decrease wallet user.

        return $cosmeticPossessed;
    }
}
