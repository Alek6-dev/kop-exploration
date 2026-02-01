<?php

declare(strict_types=1);

namespace App\Player\Domain\Repository;

use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Driver\Domain\Model\DriverInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;
use App\Shared\Infrastructure\Doctrine\Enum\ComparisonEnum;
use App\Team\Domain\Model\TeamInterface;
use App\User\Domain\Model\UserVisitorInterface;

interface PlayerRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withChampionship(ChampionshipInterface $championship): static;

    public function withDriver(DriverInterface $driver): static;

    public function withTeam(TeamInterface $team): static;

    public function withNoCompleteSelection(): static;

    public function orderByRemainingBudget(string $direction = 'DESC'): static;

    public function orderByCreatedAt(string $direction = 'DESC'): static;

    public function orderByBettingRoundCreatedAt(int $round, string $direction = 'DESC'): static;

    public function withPosition(int $position, ComparisonEnum $comparison = ComparisonEnum::EQUAL): static;

    public function withChampionshipStatus(ChampionshipStatusEnum $statusEnum): static;

    public function withUser(UserVisitorInterface $user): static;
}
