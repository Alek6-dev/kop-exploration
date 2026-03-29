<?php

declare(strict_types=1);

namespace App\CreditWallet\Domain\Enum;

enum TransactionType: string
{
    case CREDIT_WALLET = 'credit_wallet';

    case CREDIT_SPONSORSHIP = 'credit_sponsorship';

    case CONSUME_COSMETIC = 'consume_cosmetic';

    /**
     * @return TransactionType[]
     */
    public static function getTypesByOperation(TransactionOperator $operation): array
    {
        return match ($operation) {
            TransactionOperator::CONSUME => [
                self::CONSUME_COSMETIC,
            ],
            TransactionOperator::CREDIT => [
                self::CREDIT_WALLET,
                self::CREDIT_SPONSORSHIP,
            ],
        };
    }
}
