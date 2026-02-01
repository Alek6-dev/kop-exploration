<?php

declare(strict_types=1);

namespace App\Driver\Domain\Repository;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Driver\Domain\Model\DriverInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

interface DriverRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    /**
     * @return array<DriverInterface>|null
     */
    public function getAll(): ?array;

    public function withSeason(SeasonInterface $season): static;

    public function withIsReplacement(bool $isReplacement): static;

    public function withIsReplacedPermanently(bool $isReplacedPermanently): static;

    public function withReplacementPermanently(): static;

    public function withOrderByMinValue(string $direction = 'DESC'): static;

    public function withNotAlreadySelected(ChampionshipInterface $championship): static;

    public function withFullName(string $fullName): static;
}
