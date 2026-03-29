<?php

namespace App\Result\Domain\Model;

use App\Performance\Infrastructure\Doctrine\Entity\DriverPerformance;
use App\Performance\Infrastructure\Doctrine\Entity\TeamPerformance;
use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Stringifyable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use Doctrine\Common\Collections\Collection;

interface ResultInterface extends Stringifyable, Idable, Uuidable, Timestampable
{
    public function getSeason(): ?SeasonInterface;

    public function setSeason(?SeasonInterface $season): static;

    public function getRace(): ?RaceInterface;

    public function setRace(?RaceInterface $race): static;

    public function getResultLaps(): Collection;

    public function addResultLap(ResultLapInterface $resultLap): static;

    public function removeResultLap(ResultLapInterface $resultLap): void;

    public function getDriverPerformances(): ?Collection;

    public function addDriverPerformance(DriverPerformance $driverPerformance): static;

    public function removeDriverPerformance(DriverPerformance $driverPerformance): void;

    public function getTeamPerformances(): ?Collection;

    public function addTeamPerformance(TeamPerformance $teamPerformance): static;

    public function removeTeamPerformance(TeamPerformance $teamPerformance): void;
}
