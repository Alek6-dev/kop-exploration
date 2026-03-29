<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\Symfony\Command;

use App\Championship\Application\Command\UpdateChampionshipRaceStatus\UpdateChampionshipRaceStatusCommand;
use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Duel\Application\Command\SelectDriver\SelectDriverCommand;
use App\Duel\Domain\Model\DuelInterface;
use App\Duel\Domain\Repository\DuelRepositoryInterface;
use App\Player\Application\Command\DecrementDuelUsageDriver\DecrementDuelUsageDriverPlayerCommand;
use App\Player\Application\Command\DecrementStrategyUsageDriver\DecrementStrategyUsageDriverPlayerCommand;
use App\Player\Domain\Model\PlayerInterface;
use App\Season\Domain\Repository\SeasonRaceRepositoryInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Domain\Repository\StrategyRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:championship:end-strategy',
    description: 'End strategies/duels.',
)]
final class ChampionshipEndRaceCommand extends Command
{
    public function __construct(
        private readonly ChampionshipRepositoryInterface $championshipRepository,
        private readonly SeasonRaceRepositoryInterface $seasonRaceRepository,
        private readonly StrategyRepositoryInterface $strategyRepository,
        private readonly DuelRepositoryInterface $duelRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly CommandBusInterface $commandBus,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $currentDate = new \DateTimeImmutable();

        $championships = $this->championshipRepository
            ->withStatus(ChampionshipStatusEnum::ACTIVE);
        /** @var ChampionshipInterface $championship */
        foreach ($championships as $championship) {
            $championshipRace = $championship->getActiveChampionshipRace();

            if (!$championshipRace) {
                continue;
            }
            $seasonRace = $this->seasonRaceRepository
                ->withSeason($championship->getSeason())
                ->withRace($championshipRace->getRace())
                ->withLimitStrategyDate($currentDate)
                ->first()
            ;

            if (!$seasonRace) {
                continue;
            }

            // decrement driver usage for strategy
            $strategies = $this->strategyRepository
                ->withChampionship($championship)
                ->withRace($championshipRace->getRace())
            ;

            $playersToUpdate = [];
            /** @var StrategyInterface $strategy */
            foreach ($strategies as $strategy) {
                $player = $strategy->getPlayer();
                // if driver strategy is null driver 1 will be decremented
                $driverSelected = $player->getActiveSelectedDriver1();
                if (($strategy->getDriver() === $player->getActiveSelectedDriver2() && 0 < $player->getRemainingUsageDriver2()) || 0 >= $player->getRemainingUsageDriver1()) {
                    $driverSelected = $player->getActiveSelectedDriver2();
                }

                /** @var PlayerInterface $player */
                $player = $this->commandBus->dispatch(new DecrementStrategyUsageDriverPlayerCommand(
                    $driverSelected,
                    $player,
                ));

                $playersToUpdate[$player->getId()] = $player;
            }

            // decrement duel driver usage
            $duels = $this->duelRepository
                ->withChampionship($championship)
                ->withRace($championshipRace->getRace())
            ;

            /** @var DuelInterface $duel */
            foreach ($duels as $duel) {
                $driver1 = $duel->getPlayerDriver1()?->getReplacedBy() ?? $duel->getPlayerDriver1();
                $player1 = $duel->getPlayer1();
                if (\array_key_exists($player1->getId(), $playersToUpdate)) {
                    $player1 = $playersToUpdate[$player1->getId()];
                }
                $driver2 = $duel->getPlayerDriver2()?->getReplacedBy() ?? $duel->getPlayerDriver2();
                $player2 = $duel->getPlayer2();
                if (\array_key_exists($player2->getId(), $playersToUpdate)) {
                    $player2 = $playersToUpdate[$player2->getId()];
                }

                // Select default duel if player was AFK (driver 1 then driver 2)
                if (!$driver1) {
                    $driver1 = $player1->getRemainingDuelUsageDriver1() ? $player1->getActiveSelectedDriver1() : $player1->getActiveSelectedDriver2();
                    $duel = $this->commandBus->dispatch(new SelectDriverCommand(
                        $duel,
                        $player1,
                        $driver1,
                    ));

                    $this->entityManager->persist($duel);
                }

                if (!$driver2) {
                    $driver2 = $player2->getRemainingDuelUsageDriver1() ? $player2->getActiveSelectedDriver1() : $player2->getActiveSelectedDriver2();
                    $duel = $this->commandBus->dispatch(new SelectDriverCommand(
                        $duel,
                        $player2,
                        $driver2,
                    ));

                    $this->entityManager->persist($duel);
                }

                $player1 = $this->commandBus->dispatch(new DecrementDuelUsageDriverPlayerCommand(
                    $driver1,
                    $player1,
                ));

                $this->entityManager->persist($player1);

                $player2 = $this->commandBus->dispatch(new DecrementDuelUsageDriverPlayerCommand(
                    $driver2,
                    $player2,
                ));

                $this->entityManager->persist($player2);
            }

            $championshipRace = $this->commandBus->dispatch(new UpdateChampionshipRaceStatusCommand(
                $championshipRace,
                ChampionshipRaceStatusEnum::WAITING_RESULT
            ));

            $this->entityManager->persist($championshipRace);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
