<?php

declare(strict_types=1);

namespace App\CreditWallet\Domain\Model;

use App\CreditWallet\Domain\Enum\TransactionOperator;
use App\CreditWallet\Domain\Enum\TransactionType;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;

interface CreditWalletTransactionInterface extends Idable, Uuidable, Timestampable
{
    public function getType(): ?TransactionType;

    public function setType(TransactionType $transactionType): static;

    public function getOperator(): TransactionOperator;

    public function setOperator(?TransactionOperator $transactionOperator): static;

    public function getBalanceBefore(): int;

    public function setBalanceBefore(int $balance): static;

    public function getBalanceAfter(): int;

    public function setBalanceAfter(int $balance): static;

    public function getExternalPaymentId(): ?string;

    public function setExternalPaymentId(?string $externalPaymentId): static;

    public function getCreditWallet(): ?CreditWalletInterface;

    public function setCreditWallet(CreditWalletInterface $creditWallet): static;
}
