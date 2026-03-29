<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\Symfony\Command;

use App\Championship\Application\Command\Start\StartChampionshipCommand;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Shared\Application\Command\CommandBusInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:championship:start',
    description: 'Start championships without empty player slot.',
)]
final class ChampionshipStartCommand extends Command
{
    public function __construct(
        private readonly ChampionshipRepositoryInterface $championshipRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly CommandBusInterface $commandBus,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $championships = $this->championshipRepository
            ->withStatus(ChampionshipStatusEnum::CREATED)
            ->withPlayerSlotsAreFull()
        ;

        /** @var ChampionshipInterface $championship */
        foreach ($championships as $championship) {
            $championship = $this->commandBus->dispatch(new StartChampionshipCommand(
                $championship,
                $championship->getCreatedBy(),
            ));
            $this->entityManager->persist($championship);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
