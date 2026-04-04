<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Console\Notification;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'kop:notifications:publish-scheduled',
    description: 'Publie les notifications dont la date programmée est atteinte.',
)]
final class PublishScheduledNotificationsCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTimeImmutable();
        $cutoff = $now->modify('-10 minutes');

        /** @var Notification[] $pending */
        $pending = $this->em->createQueryBuilder()
            ->select('n')
            ->from(Notification::class, 'n')
            ->where('n.scheduledAt IS NOT NULL')
            ->andWhere('n.scheduledAt <= :now')
            ->andWhere('n.scheduledAt >= :cutoff')
            ->andWhere('n.publishedAt IS NULL')
            ->setParameter('now', $now)
            ->setParameter('cutoff', $cutoff)
            ->getQuery()
            ->getResult();

        if (empty($pending)) {
            $output->writeln('Aucune notification à publier.');

            return Command::SUCCESS;
        }

        foreach ($pending as $notification) {
            $notification->setPublishedAt($notification->getScheduledAt());
            $output->writeln(sprintf('Publiée : "%s"', $notification->getTitle()));
        }

        $this->em->flush();
        $output->writeln(sprintf('%d notification(s) publiée(s).', count($pending)));

        return Command::SUCCESS;
    }
}
