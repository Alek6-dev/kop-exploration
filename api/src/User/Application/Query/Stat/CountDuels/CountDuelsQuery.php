<?php

declare(strict_types=1);

namespace App\User\Application\Query\Stat\CountDuels;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class CountDuelsQuery implements QueryInterface
{
    public function __construct(
        public string $userUuid,
        public bool $win = true,
    ) {
    }
}
