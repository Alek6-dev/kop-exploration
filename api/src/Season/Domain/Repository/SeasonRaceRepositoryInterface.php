<?php

declare(strict_types=1);

namespace App\Season\Domain\Repository;

use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

interface SeasonRaceRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withSeason(SeasonInterface $season): static;

    public function withRace(RaceInterface $race): static;

    public function withLimitStrategyDate(\DateTimeImmutable $date): static;

    public function withMaxResult(int $limit): static;
}
