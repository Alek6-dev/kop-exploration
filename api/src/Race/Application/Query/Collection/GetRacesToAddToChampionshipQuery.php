<?php

declare(strict_types=1);

namespace App\Race\Application\Query\Collection;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Season\Infrastructure\Doctrine\Entity\Season;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class GetRacesToAddToChampionshipQuery implements QueryInterface
{
    public function __construct(
        public Season $season,
        public int $limit,
    ) {
    }
}
