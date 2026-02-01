<?php

declare(strict_types=1);

namespace App\Cosmetic\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Cosmetic\Domain\Enum\TypeCosmeticEnum;
use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Cosmetic\Infrastructure\ApiPlatform\OpenApi\CosmeticFilter;
use App\Cosmetic\Infrastructure\ApiPlatform\State\Processor\BuyCosmeticProcessor;
use App\Cosmetic\Infrastructure\ApiPlatform\State\Processor\SelectCosmeticProcessor;
use App\Cosmetic\Infrastructure\ApiPlatform\State\Provider\CosmeticCollectionProvider;
use App\Cosmetic\Infrastructure\ApiPlatform\State\Provider\CosmeticItemProvider;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ApiResource(
    shortName: 'Cosmetic',
    operations: [
        new Get(
            paginationEnabled: false,
            provider: CosmeticItemProvider::class,
        ),
        new GetCollection(
            filters: [CosmeticFilter::class],
            provider: CosmeticCollectionProvider::class,
        ),
        new Post(
            uriTemplate: 'cosmetics/select/{uuid}',
            input: false,
            processor: SelectCosmeticProcessor::class,
        ),
        new Post(
            uriTemplate: 'cosmetics/buy/{uuid}',
            input: false,
            processor: BuyCosmeticProcessor::class,
        ),
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
#[Vich\Uploadable]
class CosmeticResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[Assert\NotBlank]
        public ?string $name = null,
        #[Assert\NotBlank]
        public ?string $description = null,
        #[Assert\NotBlank]
        #[Assert\PositiveOrZero]
        public ?int $price = null,
        #[Assert\Type(type: TypeCosmeticEnum::class)]
        public ?TypeCosmeticEnum $type = null,
        #[Assert\CssColor(formats: 'hex_long')]
        public ?string $color = null,
        #[Assert\NotNull]
        public ?string $image1 = null,
        #[Assert\NotNull]
        public ?string $image2 = null,
        public bool $isSelected = false,
        public bool $isPossessed = false,
    ) {
    }

    public static function fromModel(CosmeticInterface $model, UserVisitorInterface $user): self
    {
        return new self(
            $model->getUuid(),
            $model->getName(),
            $model->getDescription(),
            $model->getPrice(),
            $model->getType(),
            $model->getColor(),
            $model->getRelativeImage1Path(),
            $model->getRelativeImage2Path(),
            $model->isSelectedByUser($user),
            $model->isPossessedByUser($user),
        );
    }
}
