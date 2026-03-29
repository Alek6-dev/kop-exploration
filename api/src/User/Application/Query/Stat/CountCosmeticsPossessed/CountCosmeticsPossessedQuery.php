<?php

declare(strict_types=1);

namespace App\User\Application\Query\Stat\CountCosmeticsPossessed;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class CountCosmeticsPossessedQuery implements QueryInterface
{
    public function __construct(
        public string $userUuid,
    ) {
    }
}
