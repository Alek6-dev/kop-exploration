<?php

declare(strict_types=1);

namespace App\Strategy\Domain\Repository;

use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;
use App\Shared\Infrastructure\Doctrine\Enum\ComparisonEnum;
use App\User\Domain\Model\UserVisitorInterface;

interface StrategyRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withSeason(SeasonInterface $season): static;

    public function withRace(RaceInterface $race): static;

    public function withPlayer(PlayerInterface $player): static;

    public function withStatus(ChampionshipRaceStatusEnum $status): static;

    public function withSeasonIsActive(): static;

    public function withLimitStrategyDate(\DateTimeImmutable $date): static;

    public function withChampionship(ChampionshipInterface $championship): static;

    public function withOrderByPosition(string $direction = 'DESC'): static;

    public function withOrderByScore(string $direction = 'DESC'): static;

    public function withPosition(int $position, ComparisonEnum $comparison = ComparisonEnum::EQUAL): static;

    public function withUser(UserVisitorInterface $user): static;
}
