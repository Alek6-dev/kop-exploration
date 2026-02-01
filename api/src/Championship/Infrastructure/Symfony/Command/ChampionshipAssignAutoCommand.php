<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\Symfony\Command;

use App\Bid\Application\Command\AddBid\AddBidCommand;
use App\Bid\Application\Command\IncrementBid\IncrementBidCommand;
use App\Bid\Domain\Model\BettingRoundInterface;
use App\Championship\Application\Command\NeedToAssignRaces\NeedToAssignRacesCommand;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Domain\Repository\DriverRepositoryInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Domain\Repository\TeamRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:championship:assign-auto',
    description: 'Assign drivers and team for player afk or with not enough budget.',
)]
final class ChampionshipAssignAutoCommand extends Command
{
    public function __construct(
        private readonly ChampionshipRepositoryInterface $championshipRepository,
        private readonly DriverRepositoryInterface $driverRepository,
        private readonly TeamRepositoryInterface $teamRepository,
        private readonly PlayerRepositoryInterface $playerRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly CommandBusInterface $commandBus,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $championships = $this->championshipRepository
            ->withStatus(ChampionshipStatusEnum::BID_RESULT_PROCESSED)
            ->groupByChampionship();

        /** @var ChampionshipInterface $championship */
        foreach ($championships as $championship) {
            /** @var array<PlayerInterface> $players */
            $players = $this->playerRepository
                ->withNoCompleteSelection()
                ->withChampionship($championship)
                ->orderByRemainingBudget('ASC')
                ->orderByBettingRoundCreatedAt($championship->getCurrentRound())
                ->getResult()
            ;

            if (0 === \count($players)) {
                // Every player have a full selection
                $championship = $this->commandBus->dispatch(new NeedToAssignRacesCommand(
                    $championship
                ));
                goto persist;
            }

            /** @var array<DriverInterface> $drivers */
            $drivers = $this->driverRepository
                ->withNotAlreadySelected($championship)
                ->withReplacementPermanently()
                ->withOrderByMinValue('ASC')
                ->getResult();
            /** @var array<TeamInterface> $teams */
            $teams = $this->teamRepository
                ->withNotAlreadySelected($championship)
                ->withOrderByMinValue('ASC')
                ->getResult();
            foreach ($players as $player) {
                $needAutoAssign = $player->isAfk();
                $minimumAmountNeeded = 0;
                $needSelectedDriver1 = false;
                if (!$player->getSelectedDriver1()) {
                    $minimumAmountNeeded += reset($drivers)->getMinValue();
                    $needSelectedDriver1 = true;
                }
                if (!$player->getSelectedDriver2()) {
                    $minValueDriver2 = reset($drivers)->getMinValue();
                    if ($needSelectedDriver1) {
                        $minValueDriver2 = current(\array_slice($drivers, 1, 1))->getMinValue();
                    }

                    $minimumAmountNeeded += $minValueDriver2;
                }
                if (!$player->getSelectedTeam()) {
                    $minimumAmountNeeded += reset($teams)->getMinValue();
                }
                if ($minimumAmountNeeded >= $player->getRemainingBudget()) {
                    $needAutoAssign = true;
                }

                if (!$needAutoAssign) {
                    continue;
                }

                $cheapestDriver1 = null;
                $cheapestDriver2 = null;
                $cheapestTeam = null;
                $totalAmount = null;
                if (!$player->getSelectedDriver1()) {
                    $cheapestDriver1 = array_shift($drivers);
                    $totalAmount = $cheapestDriver1->getMinValue();
                }
                if (!$player->getSelectedDriver2()) {
                    $cheapestDriver2 = array_shift($drivers);
                    $totalAmount = $cheapestDriver2->getMinValue();
                }

                if (!$player->getSelectedTeam()) {
                    $cheapestTeam = array_shift($teams);
                    $totalAmount = $cheapestTeam->getMinValue();
                }
                /** @var BettingRoundInterface $bettingRound */
                $bettingRound = $this->commandBus->dispatch(new AddBidCommand(
                    (clone $championship)->setCurrentRound($championship->getCurrentRound() + 1),
                    $player,
                    $cheapestDriver1,
                    $cheapestDriver1 ? $cheapestDriver1->getMinValue() : null,
                    $cheapestDriver2,
                    $cheapestDriver2 ? $cheapestDriver2->getMinValue() : null,
                    $cheapestTeam,
                    $cheapestTeam ? $cheapestTeam->getMinValue() : null,
                    true
                ));
                $player->setSelectedDriver1($cheapestDriver1 ?: $player->getSelectedDriver1());
                $player->setSelectedDriver2($cheapestDriver2 ?: $player->getSelectedDriver2());
                $player->setSelectedTeam($cheapestTeam ?: $player->getSelectedTeam());
                $player->setRemainingBudget($totalAmount ? $player->getRemainingBudget() - $totalAmount : $player->getRemainingBudget());
                $this->entityManager->persist($player);
                $this->entityManager->persist($bettingRound);
            }

            $championship = $this->commandBus->dispatch(new IncrementBidCommand(
                $championship
            ));

            persist:
            $this->entityManager->persist($championship);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
