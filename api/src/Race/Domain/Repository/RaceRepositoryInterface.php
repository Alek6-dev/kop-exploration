<?php

declare(strict_types=1);

namespace App\Race\Domain\Repository;

use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

interface RaceRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    /**
     * @return array<RaceInterface>|null
     */
    public function getAll(): ?array;

    public function withLimitStrategyDateGreaterThan(SeasonInterface $season, ?\DateTimeImmutable $date): static;

    public function orderByDate(string $direction = 'DESC'): static;

    public function limit(int $limit): static;
}
