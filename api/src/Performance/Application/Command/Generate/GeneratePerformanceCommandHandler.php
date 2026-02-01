<?php

declare(strict_types=1);

namespace App\Performance\Application\Command\Generate;

use App\Bonus\Application\Query\GetBonusToApplyCollection\GetBonusToApplyCollectionQuery;
use App\Bonus\Domain\Exception\BonusException;
use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Championship\Application\Command\UpdateChampionshipRaceStatus\UpdateChampionshipRaceStatusCommand;
use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Duel\Application\Command\CreateDuelDriverPerformance\CreateDuelDriverPerformanceCommand;
use App\Duel\Domain\Model\DuelDriverPerformanceInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Performance\Application\Query\GetStrategyPoint\GetStrategyPointQuery;
use App\Performance\Domain\Enum\DuelPositionPointEnum;
use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Performance\Domain\Model\TeamPerformanceInterface;
use App\Performance\Domain\Repository\DriverPerformanceRepositoryInterface;
use App\Performance\Domain\Repository\TeamPerformanceRepositoryInterface;
use App\Player\Domain\Enum\RewardEndRaceEnum;
use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Application\Command\AsCommandHandler;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Strategy\Application\Command\ApplyBonusDuelDriverPerformance\ApplyBonusDuelDriverPerformanceCommand;
use App\Strategy\Application\Command\ApplyBonusStrategyDriverPerformance\ApplyBonusStrategyDriverPerformanceCommand;
use App\Strategy\Application\Command\ApplyBonusStrategyDriverPerformance\ApplyBonusStrategyDriverPerformanceCommandHandler;
use App\Strategy\Application\Command\ApplyBonusStrategyTeamPerformance\ApplyBonusStrategyTeamPerformanceCommand;
use App\Strategy\Application\Command\CreateStrategyDriverPerformance\CreateStrategyDriverPerformanceCommand;
use App\Strategy\Application\Command\CreateStrategyTeamPerformance\CreateStrategyTeamPerformanceCommand;
use App\Strategy\Domain\Model\StrategyDriverPerformanceInterface;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Domain\Model\StrategyTeamPerformanceInterface;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommandHandler]
final readonly class GeneratePerformanceCommandHandler
{
    public function __construct(
        private ChampionshipRepositoryInterface $championshipRepository,
        private DriverPerformanceRepositoryInterface $driverPerformanceRepository,
        private TeamPerformanceRepositoryInterface $teamPerformanceRepository,
        private EntityManagerInterface $entityManager,
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(GeneratePerformanceCommand $command): void
    {
        $championships = $this->championshipRepository
            ->withStatus(ChampionshipStatusEnum::ACTIVE);
        /** @var ChampionshipInterface $championship */
        foreach ($championships as $championship) {
            $championshipRace = $championship->getResultProcessedChampionshipRace();
            if (!$championshipRace) {
                continue;
            }

            $race = $championshipRace->getRace();

            $driverPerformances = $this->driverPerformanceRepository
                ->withRace($race)
                ->withSeason($championship->getSeason())
            ;
            $strategiesToSort = [];
            /** @var StrategyInterface $strategy */
            foreach ($championship->getCurrentStrategies($race) as $strategy) {
                $i = 0;
                $currentStrategy = $strategy;
                /** @var BonusApplicationInterface[] $bonusesToApply */
                $bonusesToApply = $this->queryBus->ask(new GetBonusToApplyCollectionQuery(
                    $strategy,
                    $strategy->getPlayer(),
                    $race
                ));
                /** @var DriverPerformanceInterface $driverPerformance */
                foreach ($driverPerformances as $driverPerformance) {
                    if ($driverPerformance->getDriver() === $currentStrategy->getPlayer()->getActiveSelectedDriver1()
                        || $driverPerformance->getDriver() === $currentStrategy->getPlayer()->getActiveSelectedDriver2()
                    ) {
                        ++$i;
                        /** @var StrategyDriverPerformanceInterface $strategyDriverPerformance */
                        $strategyDriverPerformance = $this->commandBus->dispatch(new CreateStrategyDriverPerformanceCommand(
                            $currentStrategy,
                            $driverPerformance,
                            $driverPerformance->getQualificationPoints(),
                            $driverPerformance->getRacePoints(),
                            $driverPerformance->getSprintPoints(),
                            $driverPerformance->getPositionGain(),
                        ));

                        // Apply bonuses to driver strategy
                        $strategyDriverPerformance = $this->applyBonuses(
                            $bonusesToApply,
                            ApplyBonusStrategyDriverPerformanceCommand::class,
                            $strategyDriverPerformance
                        );

                        // Apply the multiplier to the main driver
                        $score = $strategyDriverPerformance->getScoreWithBonus();
                        if ($currentStrategy->getDriver() === $driverPerformance->getDriver()) {
                            $score *= ApplyBonusStrategyDriverPerformanceCommandHandler::MAIN_DRIVER_MULTIPLIER;
                        }

                        $strategyDriverPerformance->setScoreWithBonus($score);

                        $this->entityManager->persist($strategyDriverPerformance);
                        $currentStrategy->setScore($currentStrategy->getScore() + $strategyDriverPerformance->getScoreWithBonus());
                        if (2 <= $i) {
                            break;
                        }
                    }
                }

                $teamPerformances = $this->teamPerformanceRepository
                    ->withRace($race)
                    ->withSeason($championship->getSeason())
                ;
                /** @var TeamPerformanceInterface $teamPerformance */
                foreach ($teamPerformances as $teamPerformance) {
                    /* @var StrategyInterface $strategy */
                    if ($teamPerformance->getTeam() === $currentStrategy->getPlayer()->getSelectedTeam()) {
                        /** @var StrategyTeamPerformanceInterface $strategyTeamPerformance */
                        $strategyTeamPerformance = $this->commandBus->dispatch(new CreateStrategyTeamPerformanceCommand(
                            $currentStrategy,
                            $teamPerformance,
                        ));
                        $strategyTeamPerformance = $this->applyBonuses($bonusesToApply, ApplyBonusStrategyTeamPerformanceCommand::class, $strategyTeamPerformance);
                        $this->entityManager->persist($strategyTeamPerformance);
                        $currentStrategy->setScore($currentStrategy->getScore() * $strategyTeamPerformance->getMultiplier());
                        break;
                    }
                }
                $strategiesToSort[] = $currentStrategy;
            }

            usort($strategiesToSort, function (StrategyInterface $a, StrategyInterface $b) {
                return (int) $b->getScore() <=> (int) $a->getScore();
            });

            $positions = 1;
            $playersToSort = [];
            $prevStrategy = null;
            foreach ($strategiesToSort as $strategy) {
                /** @var int $points */
                $position = $prevStrategy?->getScore() === $strategy->getScore() ? $prevStrategy->getPosition() : $positions;
                $points = $this->queryBus->ask(new GetStrategyPointQuery(
                    $championship,
                    $position,
                ));
                $strategy->setPosition($position)
                    ->setPoints($points)
                ;
                $player = $strategy->getPlayer();
                $player
                    ->setScore($player->getScore() + $strategy->getScore())
                    ->setPoints($player->getPoints() + $strategy->getPoints())
                    ->setRemainingBudget($player->getRemainingBudget() + RewardEndRaceEnum::getPointsFromPosition($position)->value)
                ;
                $playersToSort[$player->getId()] = $player;
                $this->entityManager->persist($strategy);
                ++$positions;
                $prevStrategy = $strategy;
            }

            /** @var DuelInterface $duel */
            foreach ($championship->getCurrentDuels($race) as $duel) {
                $duelDrivers = [];
                /** @var BonusApplicationInterface[] $bonusesToApplyPlayer1 */
                $bonusesToApplyPlayer1 = $this->queryBus->ask(new GetBonusToApplyCollectionQuery(
                    $duel,
                    $duel->getPlayer1(),
                    $race,
                ));
                /** @var BonusApplicationInterface[] $bonusesToApplyPlayer2 */
                $bonusesToApplyPlayer2 = $this->queryBus->ask(new GetBonusToApplyCollectionQuery(
                    $duel,
                    $duel->getPlayer2(),
                    $race,
                ));

                $bonusesToApply = array_merge($bonusesToApplyPlayer1, $bonusesToApplyPlayer2);

                /** @var DriverPerformanceInterface $driverPerformance */
                foreach ($driverPerformances as $driverPerformance) {
                    if ($driverPerformance->getDriver() === $duel->getPlayerDriver1()
                        || $driverPerformance->getDriver() === $duel->getPlayerDriver2()) {
                        /** @var DuelDriverPerformanceInterface $duelDriverPerformance */
                        $duelDriverPerformance = $this->commandBus->dispatch(new CreateDuelDriverPerformanceCommand(
                            $duel,
                            $driverPerformance,
                            $driverPerformance->getQualificationPoints(),
                            $driverPerformance->getRacePoints(),
                            $driverPerformance->getSprintPoints(),
                            $driverPerformance->getPositionGain(),
                        ));

                        $duelDriverPerformance = $this->applyBonuses($bonusesToApply, ApplyBonusDuelDriverPerformanceCommand::class, $duelDriverPerformance);
                        $this->entityManager->persist($duelDriverPerformance);
                        $duelDrivers[$driverPerformance->getDriver() === $duel->getPlayerDriver1() ? 1 : 2] = [
                            'driver_performance' => $driverPerformance,
                            'score' => $duelDriverPerformance->getScoreWithBonus(),
                        ];
                        if (2 <= \count($duelDrivers)) {
                            switch (true) {
                                case $duelDrivers[1]['score'] > $duelDrivers[2]['score']:
                                    $duel->setPointsPlayer1(DuelPositionPointEnum::P_1);
                                    $duel->setPointsPlayer2(DuelPositionPointEnum::P_2);
                                    break;
                                case $duelDrivers[1]['score'] < $duelDrivers[2]['score']:
                                    $duel->setPointsPlayer2(DuelPositionPointEnum::P_1);
                                    $duel->setPointsPlayer1(DuelPositionPointEnum::P_2);
                                    break;
                                case $duelDrivers[1]['score'] === $duelDrivers[2]['score']:
                                    $duel->setPointsPlayer1(DuelPositionPointEnum::P_DEFAULT);
                                    $duel->setPointsPlayer2(DuelPositionPointEnum::P_DEFAULT);
                                    break;
                            }
                            $duel->setScorePlayer1($duelDrivers[1]['score']);
                            $duel->setScorePlayer2($duelDrivers[2]['score']);
                            $playersToSort[$duel->getPlayer1()->getId()]->setScore($playersToSort[$duel->getPlayer1()->getId()]->getScore() + $duel->getScorePlayer1());
                            $playersToSort[$duel->getPlayer1()->getId()]->setPoints($playersToSort[$duel->getPlayer1()->getId()]->getPoints() + $duel->getPointsPlayer1()->value);
                            $playersToSort[$duel->getPlayer2()->getId()]->setScore($playersToSort[$duel->getPlayer2()->getId()]->getScore() + $duel->getScorePlayer2());
                            $playersToSort[$duel->getPlayer2()->getId()]->setPoints($playersToSort[$duel->getPlayer2()->getId()]->getPoints() + $duel->getPointsPlayer2()->value);
                            break;
                        }
                    }
                }
            }

            usort($playersToSort, function (PlayerInterface $a, PlayerInterface $b) {
                $comparison = (int) $b->getPoints() <=> (int) $a->getPoints();
                if (0 === $comparison) {
                    $comparison = (int) $b->getScore() <=> (int) $a->getScore();
                }

                return $comparison;
            });

            $positions = 1;
            $prevPlayer = null;
            foreach ($playersToSort as $player) {
                $position = $positions;
                if ($prevPlayer?->getPoints() === $player->getPoints() && $prevPlayer?->getScore() === $player->getScore()) {
                    $position = $prevPlayer->getPosition();
                }
                $player->setPosition($position);

                $this->entityManager->persist($player);
                ++$positions;
                $prevPlayer = $player;
            }

            $championshipRace = $this->commandBus->dispatch(new UpdateChampionshipRaceStatusCommand(
                $championshipRace,
                ChampionshipRaceStatusEnum::OVER
            ));
            $this->entityManager->persist($championshipRace);

            $nextChampionshipRace = $championship->getNextChampionshipRace();
            if ($nextChampionshipRace) {
                $nextChampionshipRace = $this->commandBus->dispatch(new UpdateChampionshipRaceStatusCommand(
                    $nextChampionshipRace,
                    ChampionshipRaceStatusEnum::ACTIVE
                ));
                $this->entityManager->persist($nextChampionshipRace);
            } else {
                $championship->setStatus(ChampionshipStatusEnum::OVER);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param BonusApplicationInterface[] $bonusesToApply
     */
    private function applyBonuses(
        array $bonusesToApply,
        string $commandToDispatch,
        TeamPerformanceInterface|DriverPerformanceInterface $performance
    ): TeamPerformanceInterface|DriverPerformanceInterface {
        foreach ($bonusesToApply as $bonusToApply) {
            try {
                $bonusToApply = $bonusToApply->applyBonusToPerformance($performance);
                $bonusToApply->getPlayer()->setRemainingBudget($bonusToApply->getPlayer()->getRemainingBudget() - $bonusToApply->getBonus()->getPrice());
            } catch (BonusException) {
                // is not the selected entity
                continue;
            }
            $performance = $this->commandBus->dispatch(new $commandToDispatch(
                $performance,
                $bonusToApply,
            ));
        }

        return $performance;
    }
}
