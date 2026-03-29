<?php

declare(strict_types=1);

namespace App\Bid\Domain\Repository;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

interface BettingRoundRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withRound(int $round): static;

    public function withChampionship(ChampionshipInterface $championship): static;

    public function withIsSetBySystem(bool $isSetBySystem): static;

    public function orderByCreatedAt(string $direction = 'DESC'): static;
}
