<?php

declare(strict_types=1);

namespace App\CreditWallet\Application\Query\Collection;

use App\Shared\Application\Query\QueryInterface;

class GetCreditPacksQuery implements QueryInterface
{
    public function __construct(
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
