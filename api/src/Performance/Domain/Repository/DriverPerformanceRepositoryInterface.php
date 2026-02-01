<?php

declare(strict_types=1);

namespace App\Performance\Domain\Repository;

use App\Driver\Domain\Model\DriverInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

interface DriverPerformanceRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withRace(RaceInterface $race): static;

    public function withSeason(SeasonInterface $season): static;

    public function withDriver(DriverInterface $driver): static;
}
