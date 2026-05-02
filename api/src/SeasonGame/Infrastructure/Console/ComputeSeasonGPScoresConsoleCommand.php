<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\Console;

use App\SeasonGame\Application\Command\ComputeSeasonGPScores\ComputeSeasonGPScoresCommand;
use App\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'kop:season-game:compute-scores',
    description: 'Calcule les scores du mode Saison pour un GP donné.',
)]
class ComputeSeasonGPScoresConsoleCommand extends Command
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('raceUuid', InputArgument::REQUIRED, 'UUID de la course');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $raceUuid = $input->getArgument('raceUuid');

        $output->writeln(sprintf('Computing Season Game scores for race %s...', $raceUuid));

        $this->commandBus->dispatch(new ComputeSeasonGPScoresCommand($raceUuid));

        $output->writeln('Done.');

        return Command::SUCCESS;
    }
}
