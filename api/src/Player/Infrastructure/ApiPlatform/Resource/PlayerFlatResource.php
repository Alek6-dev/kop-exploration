<?php

declare(strict_types=1);

namespace App\Player\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Player\Domain\Model\PlayerInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Player',
    normalizationContext: [
        'skip_null_values' => false,
    ],
)]
class PlayerFlatResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[Assert\Uuid]
        public ?string $userUuid = null,
        #[Assert\Length(min: 3, max: 25)]
        public ?string $name = null,
        #[Assert\PositiveOrZero]
        public ?int $remainingBudget = null,
        #[Assert\PositiveOrZero]
        public ?int $remainingUsageDriver1 = null,
        #[Assert\PositiveOrZero]
        public ?int $remainingUsageDriver2 = null,
        #[Assert\PositiveOrZero]
        public ?int $remainingDuelUsageDriver1 = null,
        #[Assert\PositiveOrZero]
        public ?int $remainingDuelUsageDriver2 = null,
        #[Assert\NotNull]
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?int $point = 0,
        public ?int $position = null,
        public ?int $score = 0,
        public ?string $carImageUrl1 = null,
        public ?string $carImageUrl2 = null,
        public ?string $carColor = null,
        public ?string $helmetImageUrl1 = null,
        public ?string $helmetImageUrl2 = null,
        public ?string $helmetColor = null,
    ) {
    }

    public static function fromModel(PlayerInterface $model): self
    {
        return new self(
            $model->getUuid(),
            $model->getUser()->getUuid(),
            $model->getName(),
            $model->getRemainingBudget(),
            $model->getRemainingUsageDriver1(),
            $model->getRemainingUsageDriver2(),
            $model->getRemainingDuelUsageDriver1(),
            $model->getRemainingDuelUsageDriver2(),
            $model->getPoints(),
            $model->getPosition(),
            $model->getScore(),
            $model->getUser()->getCarCosmetic()?->getRelativeImage1Path(),
            $model->getUser()->getCarCosmetic()?->getRelativeImage2Path(),
            $model->getUser()->getCarCosmetic()?->getColor(),
            $model->getUser()->getHelmetCosmetic()?->getRelativeImage1Path(),
            $model->getUser()->getHelmetCosmetic()?->getRelativeImage2Path(),
            $model->getUser()->getHelmetCosmetic()?->getColor(),
        );
    }
}
