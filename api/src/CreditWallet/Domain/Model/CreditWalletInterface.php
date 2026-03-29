<?php

declare(strict_types=1);

namespace App\CreditWallet\Domain\Model;

use App\CreditWallet\Domain\Enum\TransactionType;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use App\User\Domain\Model\UserVisitorInterface;

interface CreditWalletInterface extends Idable, Uuidable, Timestampable
{
    public function getCredit(): ?int;

    public function setCredit(int $credit): static;

    public function makeTransaction(TransactionType $transactionType, int $cost): self;

    public function getUser(): ?UserVisitorInterface;

    public function setUser(UserVisitorInterface $user): static;

    public function getExternalId(): ?string;

    public function setExternalId(?string $externalId): static;
}
