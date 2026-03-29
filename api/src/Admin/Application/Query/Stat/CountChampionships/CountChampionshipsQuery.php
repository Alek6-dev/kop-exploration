<?php

declare(strict_types=1);

namespace App\Admin\Application\Query\Stat\CountChampionships;

use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class CountChampionshipsQuery implements QueryInterface
{
    /**
     * @param array<ChampionshipStatusEnum> $statuses
     */
    public function __construct(
        public array $statuses,
    ) {
    }
}
