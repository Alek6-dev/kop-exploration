<?php

declare(strict_types=1);

namespace App\Team\Domain\Repository;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;
use App\Team\Domain\Model\TeamInterface;

interface TeamRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    /**
     * @return array<TeamInterface>|null
     */
    public function getAll(): ?array;

    public function withSeason(SeasonInterface $season): static;

    public function withOrderByMinValue(string $direction = 'DESC'): static;

    public function withNotAlreadySelected(ChampionshipInterface $championship): static;
}
