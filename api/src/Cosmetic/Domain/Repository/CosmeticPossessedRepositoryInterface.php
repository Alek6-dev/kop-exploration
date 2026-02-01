<?php

declare(strict_types=1);

namespace App\Cosmetic\Domain\Repository;

use App\Cosmetic\Domain\Enum\TypeCosmeticEnum;
use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;
use App\User\Domain\Model\UserVisitorInterface;

interface CosmeticPossessedRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withCosmetic(CosmeticInterface $cosmetic): static;

    public function withUser(UserVisitorInterface $user): static;

    public function withIsSelected(bool $select): static;

    public function withTypeCosmetic(TypeCosmeticEnum $type): static;
}
