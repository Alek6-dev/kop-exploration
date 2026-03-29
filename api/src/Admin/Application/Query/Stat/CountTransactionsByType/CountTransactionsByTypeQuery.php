<?php

declare(strict_types=1);

namespace App\Admin\Application\Query\Stat\CountTransactionsByType;

use App\CreditWallet\Domain\Enum\TransactionType;
use App\Shared\Application\Query\QueryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @implements QueryInterface<TranslatorInterface>
 */
final readonly class CountTransactionsByTypeQuery implements QueryInterface
{
    public function __construct(
        public TransactionType $type,
    ) {
    }
}
