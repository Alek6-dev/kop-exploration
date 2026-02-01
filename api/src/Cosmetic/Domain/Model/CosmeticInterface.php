<?php

namespace App\Cosmetic\Domain\Model;

use App\Cosmetic\Domain\Enum\TypeCosmeticEnum;
use App\Shared\Domain\Model\Behaviors\HasColor;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Stringifyable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Component\HttpFoundation\File\File;

interface CosmeticInterface extends Stringifyable, Idable, Uuidable, Timestampable, HasColor
{
    public function getName(): ?string;

    public function setName(?string $name): static;

    public function getDescription(): ?string;

    public function setDescription(?string $description): static;

    public function getPrice(): ?int;

    public function setPrice(?int $price): static;

    public function getType(): ?TypeCosmeticEnum;

    public function setType(?TypeCosmeticEnum $type): static;

    public function getImage1File(): ?File;

    public function setImage1File(?File $image1File): static;

    public function getImage1(): ?string;

    public function setImage1(?string $image1): static;

    public function getImage2File(): ?File;

    public function setImage2File(?File $image2File): static;

    public function getImage2(): ?string;

    public function setImage2(?string $image2): static;

    public function isPossessedByUser(UserVisitorInterface $user): bool;

    public function isSelectedByUser(UserVisitorInterface $user): bool;

    public function getRelativeImage1Path(): ?string;

    public function getRelativeImage2Path(): ?string;

    public function isDefault(): bool;

    public function setIsDefault(bool $isDefault): static;
}
