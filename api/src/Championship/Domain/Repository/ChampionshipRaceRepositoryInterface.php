<?php

declare(strict_types=1);

namespace App\Championship\Domain\Repository;

use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

interface ChampionshipRaceRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withChampionship(ChampionshipInterface $championship): static;

    public function withSeason(SeasonInterface $season): static;

    public function withRace(RaceInterface $race): static;

    public function withStatus(ChampionshipRaceStatusEnum $status): static;

    public function withLimitStrategyDate(\DateTimeImmutable $date): static;
}
