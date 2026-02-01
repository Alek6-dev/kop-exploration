<?php

declare(strict_types=1);

namespace App\Bonus\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Bonus\Application\Dto\SelectBonusDto;
use App\Bonus\Application\Dto\UnselectBonusDto;
use App\Bonus\Domain\Enum\AttributeEnum;
use App\Bonus\Domain\Enum\BonusTypeEnum;
use App\Bonus\Domain\Enum\OperationEnum;
use App\Bonus\Domain\Enum\TargetTypeEnum;
use App\Bonus\Domain\Model\BonusInterface;
use App\Bonus\Infrastructure\ApiPlatform\OpenApi\BonusFilter;
use App\Bonus\Infrastructure\ApiPlatform\State\Processor\SelectBonusProcessor;
use App\Bonus\Infrastructure\ApiPlatform\State\Processor\UnselectBonusProcessor;
use App\Bonus\Infrastructure\ApiPlatform\State\Provider\BonusCollectionProvider;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Bonus',
    operations: [
        new GetCollection(
            uriTemplate: '/bonus',
            paginationEnabled: false,
            filters: [BonusFilter::class],
            provider: BonusCollectionProvider::class,
        ),
        new Post(
            uriTemplate: '/bonus/select',
            input: SelectBonusDto::class,
            output: false,
            processor: SelectBonusProcessor::class,
        ),
        new Post(
            uriTemplate: '/bonus/unselect',
            input: UnselectBonusDto::class,
            output: false,
            processor: UnselectBonusProcessor::class,
        ),
    ],
    normalizationContext: [
        'skip_null_values' => false,
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class BonusResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public string $uuid,
        public string $name,
        public string $description,
        public string $example,
        public string $icon,
        public ?int $cumulativeTimes,
        public int $price,
        public BonusTypeEnum $type,
        public TargetTypeEnum $targetType,
        public OperationEnum $operation,
        public AttributeEnum $attribute,
    ) {
    }

    public static function fromModel(BonusInterface $bonus): self
    {
        return new self(
            $bonus->getUuid(),
            $bonus->getName(),
            $bonus->getDescription(),
            $bonus->getExample(),
            $bonus->getIcon(),
            $bonus->getCumulativeTimes(),
            $bonus->getPrice(),
            $bonus->getType(),
            $bonus->getTargetType(),
            $bonus->getOperation(),
            $bonus->getAttribute(),
        );
    }
}
