<?php

declare(strict_types=1);

namespace App\User\Application\Query\Collection;

use App\Shared\Application\Query\QueryInterface;
use App\User\Domain\Model\UserVisitorInterface;

/**
 * @implements QueryInterface<UserVisitorInterface>
 */
final readonly class GetUserVisitorsQuery implements QueryInterface
{
    public function __construct(
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
