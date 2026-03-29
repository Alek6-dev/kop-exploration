<?php

namespace App\Cosmetic\Domain\Model;

use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use App\User\Domain\Model\UserVisitorInterface;

interface CosmeticPossessedInterface extends Idable, Uuidable, Timestampable
{
    public function getUser(): UserVisitorInterface;

    public function setUser(UserVisitorInterface $user): static;

    public function getCosmetic(): CosmeticInterface;

    public function setCosmetic(CosmeticInterface $cosmetic): static;

    public function isSelected(): bool;

    public function setIsSelected(bool $select): static;

    public function getPrice(): int;

    public function setPrice(int $price): static;
}
