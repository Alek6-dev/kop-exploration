<?php

declare(strict_types=1);

namespace App\Performance\Domain\Repository;

use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;
use App\Team\Domain\Model\TeamInterface;

interface TeamPerformanceRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withRace(RaceInterface $race): static;

    public function withSeason(SeasonInterface $season): static;

    public function withTeam(TeamInterface $team): static;
}
