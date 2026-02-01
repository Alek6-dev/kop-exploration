<?php

declare(strict_types=1);

namespace App\Result\Domain\Repository;

use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

interface ResultRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function removeManyBySeasonRaceType(SeasonInterface $season, RaceInterface $race): void;

    public function withSeason(SeasonInterface $season): static;

    public function withRace(RaceInterface $race): static;
}
