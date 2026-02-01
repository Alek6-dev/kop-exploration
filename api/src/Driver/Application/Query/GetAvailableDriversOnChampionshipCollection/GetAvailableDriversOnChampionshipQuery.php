<?php

declare(strict_types=1);

namespace App\Driver\Application\Query\GetAvailableDriversOnChampionshipCollection;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class GetAvailableDriversOnChampionshipQuery implements QueryInterface
{
    public function __construct(
        public ChampionshipInterface $championship,
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
