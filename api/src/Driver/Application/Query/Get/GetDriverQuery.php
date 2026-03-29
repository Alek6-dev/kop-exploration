<?php

declare(strict_types=1);

namespace App\Driver\Application\Query\Get;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class GetDriverQuery implements QueryInterface
{
    public function __construct(
        public string $uuid,
    ) {
    }
}
