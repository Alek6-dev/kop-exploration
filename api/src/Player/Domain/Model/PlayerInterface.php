<?php

declare(strict_types=1);

namespace App\Player\Domain\Model;

use App\Bid\Domain\Model\BettingRoundInterface;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Driver\Domain\Model\DriverInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Performance\Domain\Model\PerformanceInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Domain\Model\Behaviors\Stringifyable;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Team\Domain\Model\TeamInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\Common\Collections\Collection;

interface PlayerInterface extends Stringifyable, PerformanceInterface
{
    public function getRemainingBudget(): ?int;

    public function setRemainingBudget(?int $remainingBudget): static;

    public function getSelectedTeam(): ?TeamInterface;

    public function setSelectedTeam(?TeamInterface $team): static;

    public function getSelectedDriver1(): ?DriverInterface;

    public function setSelectedDriver1(?DriverInterface $driver): static;

    public function setRemainingUsageDriver1(?int $remainingUsage): static;

    public function getRemainingUsageDriver1(): ?int;

    public function setRemainingDuelUsageDriver1(?int $remainingUsage): static;

    public function getRemainingDuelUsageDriver1(): ?int;

    public function getSelectedDriver2(): ?DriverInterface;

    public function setSelectedDriver2(?DriverInterface $driver): static;

    public function setRemainingUsageDriver2(?int $remainingUsage): static;

    public function getRemainingUsageDriver2(): ?int;

    public function setRemainingDuelUsageDriver2(?int $remainingUsage): static;

    public function getRemainingDuelUsageDriver2(): ?int;

    public function getChampionship(): ?ChampionshipInterface;

    public function setChampionship(?ChampionshipInterface $championship): static;

    public function getUser(): ?UserVisitorInterface;

    public function setUser(?UserVisitorInterface $user): static;

    public function setName(string $name): static;

    public function getName(): ?string;

    public function getBettingRounds(): ?Collection;

    public function addBettingRound(BettingRoundInterface $bettingRound): void;

    public function removeBettingRound(BettingRoundInterface $bettingRound): void;

    public function getStrategies(): ?Collection;

    public function addStrategy(StrategyInterface $strategy): static;

    public function removeStrategy(StrategyInterface $strategy): void;

    public function haveBidOnRound(ChampionshipInterface|int $championship): bool;

    public function getLastBettingRound(): ?BettingRoundInterface;

    public function getBettingRound(int $round): ?BettingRoundInterface;

    public function isAfk(): bool;

    public function getCurrentStrategy(): ?StrategyInterface;

    public function getDuels(): ?Collection;

    public function getCurrentDuel(): ?DuelInterface;

    public function getActiveSelectedDriver1(): ?DriverInterface;

    public function getActiveSelectedDriver2(): ?DriverInterface;

    public function getBonusUsages(): ?Collection;

    public function getBonusTargets(): ?Collection;

    public function getBonusUsagesOnRace(RaceInterface $race): ?Collection;

    public function getStrategyBonusesTargetingCurrentPlayerOnRace(RaceInterface $race): ?Collection;

    public function getDuelBonusesTargetingCurrentPlayerOnRace(RaceInterface $race): ?Collection;
}
