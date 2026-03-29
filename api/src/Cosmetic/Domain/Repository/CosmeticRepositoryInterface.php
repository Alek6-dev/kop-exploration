<?php

declare(strict_types=1);

namespace App\Cosmetic\Domain\Repository;

use App\Cosmetic\Domain\Enum\TypeCosmeticEnum;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;
use App\User\Domain\Model\UserVisitorInterface;

interface CosmeticRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withName(string $name): static;

    public function withType(TypeCosmeticEnum $type): static;

    public function withOrderByPossessed(UserVisitorInterface $user): static;

    public function withIsDefault(bool $isDefault = false): static;
}
