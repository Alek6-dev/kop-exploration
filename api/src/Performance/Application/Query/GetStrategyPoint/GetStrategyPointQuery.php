<?php

declare(strict_types=1);

namespace App\Performance\Application\Query\GetStrategyPoint;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class GetStrategyPointQuery implements QueryInterface
{
    public function __construct(
        public ChampionshipInterface $championship,
        public int $position,
    ) {
    }
}
