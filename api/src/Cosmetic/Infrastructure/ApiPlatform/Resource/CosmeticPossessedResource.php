<?php

declare(strict_types=1);

namespace App\Cosmetic\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Cosmetic\Domain\Enum\TypeCosmeticEnum;
use App\Cosmetic\Domain\Model\CosmeticInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'CosmeticPossessed',
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class CosmeticPossessedResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[Assert\Type(type: TypeCosmeticEnum::class)]
        public ?TypeCosmeticEnum $type = null,
        #[Assert\NotNull]
        public ?string $name = null,
        #[Assert\CssColor(formats: 'hex_long')]
        public ?string $color = null,
        #[Assert\NotNull]
        public ?string $image1 = null,
        #[Assert\NotNull]
        public ?string $image2 = null,
    ) {
    }

    public static function fromModel(CosmeticInterface $model): self
    {
        return new self(
            $model->getUuid(),
            $model->getType(),
            $model->getName(),
            $model->getColor(),
            $model->getRelativeImage1Path(),
            $model->getRelativeImage2Path(),
        );
    }
}
