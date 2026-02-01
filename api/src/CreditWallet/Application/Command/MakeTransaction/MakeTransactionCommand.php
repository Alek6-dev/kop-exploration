<?php

namespace App\CreditWallet\Application\Command\MakeTransaction;

use App\CreditWallet\Domain\Enum\TransactionType;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
readonly class MakeTransactionCommand implements CommandInterface
{
    public function __construct(
        public string $walletUuid,
        public TransactionType $transactionType,
        public int $cost,
    ) {
    }
}
