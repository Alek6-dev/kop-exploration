<?php

declare(strict_types=1);

namespace App\CreditWallet\Domain\Model;

use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;

interface CreditPackInterface extends Idable, Uuidable, Timestampable
{
    public function getProductId(): ?string;

    public function setProductId(string $productId): static;

    public function setCredit(int $credit): static;

    public function getCredit(): ?int;

    public function setMessage(string $message): static;

    public function getMessage(): ?string;

    public function setPrice(float $price): static;

    public function getPrice(): ?float;
}
