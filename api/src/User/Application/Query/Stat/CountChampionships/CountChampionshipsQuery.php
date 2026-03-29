<?php

declare(strict_types=1);

namespace App\User\Application\Query\Stat\CountChampionships;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class CountChampionshipsQuery implements QueryInterface
{
    public function __construct(
        public string $userUuid,
        public ?int $position = null,
    ) {
    }
}
