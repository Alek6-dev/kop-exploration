<?php

declare(strict_types=1);

namespace App\Bonus\Domain\Repository;

use App\Bonus\Domain\Enum\AttributeEnum;
use App\Bonus\Domain\Enum\BonusTypeEnum;
use App\Bonus\Domain\Enum\OperationEnum;
use App\Bonus\Domain\Enum\SubTargetTypeEnum;
use App\Bonus\Domain\Enum\TargetTypeEnum;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

interface BonusRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withIsEnabled(bool $isEnabled = true): static;

    public function withType(BonusTypeEnum $type): static;

    public function withTargetType(TargetTypeEnum $targetType): static;

    public function withSubTargetType(SubTargetTypeEnum $subTargetType): static;

    public function withAttribute(AttributeEnum $attribute): static;

    public function withOperation(OperationEnum $operation): static;

    public function withIsJoker(bool $isJoker): static;

    public function withOrderByPrice(string $direction = 'DESC'): static;

    public function withOrderBySort(string $direction = 'DESC'): static;
}
