<?php

declare(strict_types=1);

namespace App\CreditWallet\Domain\Enum;

enum TransactionOperator: string
{
    case CREDIT = 'credit';
    case CONSUME = 'consume';

    public static function getOperatorByType(TransactionType $type): self
    {
        return match ($type) {
            TransactionType::CONSUME_COSMETIC,
            TransactionType::ADMIN_DEDUCTION => TransactionOperator::CONSUME,
            TransactionType::CREDIT_WALLET,
            TransactionType::CREDIT_SPONSORSHIP,
            TransactionType::ADMIN_GRANT => TransactionOperator::CREDIT,
        };
    }
}
