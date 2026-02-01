<?php

declare(strict_types=1);

namespace App\Season\Application\Query\Get;

use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<SeasonInterface>
 */
final readonly class GetLastSeasonActiveQuery implements QueryInterface
{
    public function __construct(
    ) {
    }
}
