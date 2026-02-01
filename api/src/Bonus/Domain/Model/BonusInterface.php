<?php

declare(strict_types=1);

namespace App\Bonus\Domain\Model;

use App\Bonus\Domain\Enum\AttributeEnum;
use App\Bonus\Domain\Enum\BonusTypeEnum;
use App\Bonus\Domain\Enum\OperationEnum;
use App\Bonus\Domain\Enum\SubTargetTypeEnum;
use App\Bonus\Domain\Enum\TargetTypeEnum;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use Symfony\Component\HttpFoundation\File\File;

interface BonusInterface extends Idable, Uuidable, Timestampable
{
    public function getName(): ?string;

    public function setName(string $name): static;

    public function getDescription(): ?string;

    public function setDescription(string $description): static;

    public function getExample(): ?string;

    public function setExample(string $example): static;

    public function getIconFile(): ?File;

    public function setIconFile(?File $iconFile): static;

    public function getIcon(): ?string;

    public function setIcon(string $icon): static;

    public function getPrice(): ?int;

    public function setPrice(int $price): static;

    public function getType(): ?BonusTypeEnum;

    public function setType(?BonusTypeEnum $type): static;

    public function getTargetType(): ?TargetTypeEnum;

    public function setTargetType(TargetTypeEnum $targetType): static;

    public function getSubTargetType(): ?SubTargetTypeEnum;

    public function setSubTargetType(SubTargetTypeEnum $subTargetType): static;

    public function getAttribute(): ?AttributeEnum;

    public function setAttribute(?AttributeEnum $attribute): static;

    public function getOperation(): ?OperationEnum;

    public function setOperation(?OperationEnum $operation): static;

    public function getValue(): ?int;

    public function setValue(?int $value): static;

    public function isJoker(): bool;

    public function setIsJoker(bool $isJoker): static;

    public function isEnabled(): bool;

    public function setIsEnabled(bool $isEnabled): static;

    public function getSort(): ?int;

    public function setSort(int $sort): static;

    public function getCumulativeTimes(): ?int;

    public function setCumulativeTimes(int $number): static;
}
