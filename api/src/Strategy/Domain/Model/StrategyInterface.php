<?php

namespace App\Strategy\Domain\Model;

use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Championship\Domain\Model\JoinChampionshipInterface;
use App\Driver\Domain\Model\DriverInterface;
use App\Performance\Domain\Model\PerformanceInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Race\Domain\Model\JoinRaceInterface;
use Doctrine\Common\Collections\Collection;

interface StrategyInterface extends PerformanceInterface, JoinRaceInterface, JoinChampionshipInterface
{
    public function getPlayer(): ?PlayerInterface;

    public function setPlayer(PlayerInterface $player): static;

    public function getDriver(): ?DriverInterface;

    public function setDriver(?DriverInterface $driver): static;

    public function getDriverPerformances(): ?Collection;

    public function addDriverPerformance(StrategyDriverPerformanceInterface $performance): static;

    public function removeDriverPerformance(StrategyDriverPerformanceInterface $performance): void;

    public function getTeamPerformance(): ?StrategyTeamPerformanceInterface;

    public function setTeamPerformance(StrategyTeamPerformanceInterface $performance): static;

    public function isActive(): bool;

    public function getBonusApplication(): ?BonusApplicationInterface;
}
