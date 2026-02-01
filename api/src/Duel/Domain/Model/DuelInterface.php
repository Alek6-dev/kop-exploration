<?php

namespace App\Duel\Domain\Model;

use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Championship\Domain\Model\JoinChampionshipInterface;
use App\Driver\Domain\Model\DriverInterface;
use App\Performance\Domain\Enum\DuelPositionPointEnum;
use App\Player\Domain\Model\PlayerInterface;
use App\Race\Domain\Model\JoinRaceInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use Doctrine\Common\Collections\Collection;

interface DuelInterface extends JoinRaceInterface, JoinChampionshipInterface, Idable, Uuidable, Timestampable
{
    public function getPlayer1(): ?PlayerInterface;

    public function setPlayer1(PlayerInterface $player): static;

    public function getPlayer2(): ?PlayerInterface;

    public function setPlayer2(PlayerInterface $player): static;

    public function getPlayerDriver1(): ?DriverInterface;

    public function setPlayerDriver1(?DriverInterface $driver): static;

    public function getPlayerDriver2(): ?DriverInterface;

    public function setPlayerDriver2(?DriverInterface $driver): static;

    public function getDriverPerformances(): ?Collection;

    public function addDriverPerformance(DuelDriverPerformanceInterface $performance): static;

    public function removeDriverPerformance(DuelDriverPerformanceInterface $performance): void;

    public function getPointsPlayer1(): DuelPositionPointEnum;

    public function setPointsPlayer1(DuelPositionPointEnum $points): static;

    public function getPointsPlayer2(): DuelPositionPointEnum;

    public function setPointsPlayer2(DuelPositionPointEnum $points): static;

    public function getScorePlayer1(): ?int;

    public function setScorePlayer1(int $score): static;

    public function getScorePlayer2(): ?int;

    public function setScorePlayer2(int $score): static;

    public function isActive(): bool;

    public function getBonusApplications(): ?Collection;

    public function getBonusApplicationByPlayer1OnRace(RaceInterface $race): ?BonusApplicationInterface;

    public function getBonusApplicationByPlayer2OnRace(RaceInterface $race): ?BonusApplicationInterface;

    public function getBonusAppliedToPlayer1OnRace(RaceInterface $race): ?Collection;

    public function getBonusAppliedToPlayer2OnRace(RaceInterface $race): ?Collection;
}
