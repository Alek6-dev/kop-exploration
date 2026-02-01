<?php

declare(strict_types=1);

namespace App\CreditWallet\Application\Query\Get;

use App\CreditWallet\Domain\Model\CreditPackInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<CreditPackInterface>
 */
final readonly class GetCreditPackQuery implements QueryInterface
{
    public function __construct(
        public string $uuid,
    ) {
    }
}
