<?php

declare(strict_types=1);

namespace App\Team\Application\Query\Get;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class GetTeamQuery implements QueryInterface
{
    public function __construct(
        public string $uuid,
    ) {
    }
}
