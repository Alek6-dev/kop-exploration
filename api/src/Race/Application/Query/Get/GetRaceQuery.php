<?php

declare(strict_types=1);

namespace App\Race\Application\Query\Get;

use App\Race\Domain\Model\RaceInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<RaceInterface>
 */
final readonly class GetRaceQuery implements QueryInterface
{
    public function __construct(
        public string $uuid,
    ) {
    }
}
