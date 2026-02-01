<?php

declare(strict_types=1);

namespace App\Bid\Application\Command\AddBid;

use App\Bid\Domain\Exception\BidException;
use App\Bid\Domain\Model\BettingRoundInterface;
use App\Bid\Infrastructure\Doctrine\Entity\BettingRound;
use App\Bid\Infrastructure\Doctrine\Entity\BettingRoundDriver;
use App\Bid\Infrastructure\Doctrine\Entity\BettingRoundTeam;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Exception\ChampionshipException;
use App\Driver\Domain\Repository\DriverRepositoryInterface;
use App\Player\Domain\Exception\PlayerException;
use App\Shared\Application\Command\AsCommandHandler;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Domain\Repository\TeamRepositoryInterface;

#[AsCommandHandler]
final readonly class AddBidCommandHandler
{
    public function __construct(
        private DriverRepositoryInterface $driverRepository,
        private TeamRepositoryInterface $teamRepository,
    ) {
    }

    public function __invoke(AddBidCommand $command): BettingRoundInterface
    {
        if (ChampionshipStatusEnum::BID_IN_PROGRESS !== $command->championship->getStatus() && !$command->isSetBySystem) {
            throw ChampionshipException::notReady($command->championship->getUuid());
        }

        if ($command->player->getBettingRounds()->exists(fn (int $key, BettingRoundInterface $bettingRound) => $bettingRound->getRound() === $command->championship->getCurrentRound())) {
            throw BidException::bidAlreadySaved($command->player->getUuid(), $command->championship->getCurrentRound());
        }

        $this->assertPlayerMustSelectMoreEntities($command);

        $this->assertPlayerHasAlreadySelectedEntities($command);

        $this->assetTotalAmountIsTooHigh($command);

        $this->assertMinimumBidAmountsAreTooLow($command);

        $this->assertEntitiesAreAvailable($command);

        $bettingRound = (new BettingRound())
            ->setPlayer($command->player)
            ->setIsSetBySystem($command->isSetBySystem)
            ->setRound($command->championship->getCurrentRound())
        ;

        if ($command->driver1) {
            $bettingRoundDriver1 = (new BettingRoundDriver())
                ->setDriver($command->driver1)
                ->setBidAmount($command->driver1BidAmount)
            ;

            $bettingRound->addBettingRoundDriver($bettingRoundDriver1);
        }

        if ($command->driver2) {
            $bettingRoundDriver2 = (new BettingRoundDriver())
                ->setDriver($command->driver2)
                ->setBidAmount($command->driver2BidAmount)
            ;

            $bettingRound->addBettingRoundDriver($bettingRoundDriver2);
        }

        if ($command->team) {
            $bettingRoundTeam = (new BettingRoundTeam())
                ->setTeam($command->team)
                ->setBidAmount($command->teamBidAmount)
            ;

            $bettingRound->setBettingRoundTeam($bettingRoundTeam);
        }

        return $bettingRound;
    }

    private function assertPlayerHasAlreadySelectedEntities(AddBidCommand $command): void
    {
        if ($command->player->getSelectedTeam() && $command->team) {
            throw BidException::playerHasAlreadySelectedTeam($command->player->getUuid());
        }

        if ($command->player->getSelectedDriver1() && $command->player->getSelectedDriver2()
            && ($command->driver1 || $command->driver2)
        ) {
            throw BidException::playerHasAlreadySelectedDrivers($command->player->getUuid());
        }
    }

    private function assertMinimumBidAmountsAreTooLow(AddBidCommand $command): void
    {
        if ($command->driver1 && $command->driver1->getMinValue() > (int) $command->driver1BidAmount) {
            throw BidException::bidAmountMustBeGreaterThanMinValue($command->driver1BidAmount, $command->driver1->getMinValue(), $command->driver1->getUuid());
        }

        if ($command->driver2 && $command->driver2->getMinValue() > (int) $command->driver2BidAmount) {
            throw BidException::bidAmountMustBeGreaterThanMinValue($command->driver2BidAmount, $command->driver2->getMinValue(), $command->driver2->getUuid());
        }

        if ($command->team && $command->team->getMinValue() > (int) $command->teamBidAmount) {
            throw BidException::bidAmountMustBeGreaterThanMinValue($command->teamBidAmount, $command->team->getMinValue(), $command->team->getUuid());
        }
    }

    private function assetTotalAmountIsTooHigh(AddBidCommand $command): void
    {
        $totalAmount = (int) $command->driver1BidAmount + (int) $command->driver2BidAmount + (int) $command->teamBidAmount;
        if (0 > ($command->player->getRemainingBudget() - $totalAmount) && !$command->isSetBySystem) {
            throw PlayerException::amountToSubtractIsTooHigh($command->player->getUuid(), $totalAmount, $command->player->getRemainingBudget());
        }
    }

    public function assertEntitiesAreAvailable(AddBidCommand $command): void
    {
        if ($command->driver1 && 0 === $this->driverRepository
                ->withNotAlreadySelected($command->championship)
                ->withUuid($command->driver1->getUuid())
                ->count()
        ) {
            throw BidException::driverAlreadySelectedByAnotherPlayer($command->driver1->getUuid());
        }

        if ($command->driver2 && 0 === $this->driverRepository
                ->withNotAlreadySelected($command->championship)
                ->withUuid($command->driver2->getUuid())
                ->count()
        ) {
            throw BidException::driverAlreadySelectedByAnotherPlayer($command->driver1->getUuid());
        }

        if ($command->team && 0 === $this->teamRepository
                ->withNotAlreadySelected($command->championship)
                ->withUuid($command->team->getUuid())
                ->count()
        ) {
            throw BidException::teamAlreadySelectedByAnotherPlayer($command->team->getUuid());
        }
    }

    public function assertPlayerMustSelectMoreEntities(AddBidCommand $command): void
    {
        if (!$command->player->getSelectedTeam() && !$command->team) {
            /** @var ?TeamInterface $cheapestTeam */
            $cheapestTeam = $this->teamRepository
                ->withNotAlreadySelected($command->championship)
                ->withOrderByMinValue('ASC')
                ->first()
            ;
            if (!$cheapestTeam || $cheapestTeam->getMinValue() < ($command->player->getRemainingBudget() - (int) $command->driver1BidAmount - (int) $command->driver2BidAmount)) {
                throw BidException::needToSelectATeam($command->player->getUuid());
            }
        }

        $countDrivers = 0;
        $countDrivers += $command->player->getSelectedDriver1() ? 1 : 0;
        $countDrivers += $command->player->getSelectedDriver2() ? 1 : 0;
        $countDrivers += $command->driver1 ? 1 : 0;
        $countDrivers += $command->driver2 ? 1 : 0;

        if (2 > $countDrivers) {
            throw BidException::needToSelectOneMoreDriver($command->player->getUuid());
        }
    }
}
