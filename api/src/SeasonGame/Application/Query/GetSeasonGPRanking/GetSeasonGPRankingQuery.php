<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Query\GetSeasonGPRanking;

use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<self>
 */
class GetSeasonGPRankingQuery implements QueryInterface
{
    public function __construct(
        public string $raceUuid,
    ) {
    }
}
