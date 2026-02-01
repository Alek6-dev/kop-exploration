<?php

declare(strict_types=1);

namespace App\CreditWallet\Application\Query\StripeLink;

use App\CreditWallet\Domain\Model\CreditPackInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<CreditPackInterface>
 */
final readonly class StripeLinkQuery implements QueryInterface
{
    public function __construct(
        public string $productId,
        public int $credit,
        public string $urlCallback,
    ) {
    }
}
