<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\Symfony\Command;

use App\Championship\Application\Command\Active\ActiveChampionshipCommand;
use App\Championship\Application\Command\DeleteBySystem\DeleteChampionshipBySystemCommand;
use App\Championship\Domain\Enum\ChampionshipNumberRaceEnum;
use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Championship\Infrastructure\Doctrine\Entity\ChampionshipRace;
use App\Duel\Application\Command\CreateDuel\CreateDuelCommand;
use App\Race\Application\Query\Collection\GetRacesToAddToChampionshipQuery;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Strategy\Application\Command\CreateStrategy\CreateStrategyCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:championship:assign-races',
    description: 'Assign races to championship',
)]
final class ChampionshipAssignRacesCommand extends Command
{
    public function __construct(
        private readonly ChampionshipRepositoryInterface $championshipRepository,
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $championships = $this->championshipRepository
            ->withStatus(ChampionshipStatusEnum::NEED_TO_ASSIGN_RACES);

        /** @var ChampionshipInterface $championship */
        foreach ($championships as $championship) {
            /** @var ?array $races */
            $races = $this->queryBus->ask(new GetRacesToAddToChampionshipQuery(
                $championship->getSeason(),
                $championship->getNumberOfRaces()->value
            ))->getAll() ?? [];

            try {
                $championship->setNumberOfRaces(ChampionshipNumberRaceEnum::from(\count($races)));
            } catch (\ValueError) {
                $championship = $this->commandBus->dispatch(
                    new DeleteChampionshipBySystemCommand(
                        $championship
                    )
                );
                goto persist;
            }

            // TODO: CommandBus
            $firstRace = true;
            /** @var RaceInterface $race */
            foreach ($races as $race) {
                $championship->addChampionshipRace((new ChampionshipRace())
                    ->setChampionship($championship)
                    ->setRace($race)
                    ->setStatus($firstRace ? ChampionshipRaceStatusEnum::ACTIVE : ChampionshipRaceStatusEnum::CREATED)
                );
                foreach ($championship->getPlayers() as $player) {
                    $strategy = $this->commandBus->dispatch(new CreateStrategyCommand(
                        $championship,
                        $race,
                        $player,
                    ));
                    $this->entityManager->persist($strategy);
                }
                $players = $championship->getPlayers()->toArray();
                while (\count($players) > 0) {
                    $playerIndex1 = array_rand($players);
                    $player1 = $players[$playerIndex1];
                    unset($players[$playerIndex1]);

                    $playerIndex2 = array_rand($players);
                    $player2 = $players[$playerIndex2];
                    unset($players[$playerIndex2]);

                    $duel = $this->commandBus->dispatch(new CreateDuelCommand(
                        $championship,
                        $race,
                        $player1,
                        $player2,
                    ));
                    $this->entityManager->persist($duel);
                }

                $firstRace = false;
            }

            $championship = $this->commandBus->dispatch(new ActiveChampionshipCommand(
                $championship
            ));

            persist:
            $this->entityManager->persist($championship);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
