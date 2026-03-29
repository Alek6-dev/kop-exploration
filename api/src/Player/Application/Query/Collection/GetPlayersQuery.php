<?php

declare(strict_types=1);

namespace App\Player\Application\Query\Collection;

use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<PlayerInterface>
 */
final readonly class GetPlayersQuery implements QueryInterface
{
    public function __construct(
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
