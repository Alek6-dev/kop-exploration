<?php

declare(strict_types=1);

namespace App\Driver\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Infrastructure\ApiPlatform\State\Provider\DriversAvailableByChampionshipCollectionProvider;
use App\Team\Infrastructure\ApiPlatform\Resource\TeamResource;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ApiResource(
    shortName: 'Driver',
    operations: [
        new GetCollection(
            uriTemplate: '/championships/{championshipUuid}/drivers-available',
            uriVariables: [
                'championshipUuid' => 'string',
            ],
            paginationEnabled: false,
            provider: DriversAvailableByChampionshipCollectionProvider::class,
        ),
    ],
    normalizationContext: [
        'groups' => ['driver'],
        'skip_null_values' => false,
        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s',
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
#[Vich\Uploadable]
class DriverResource
{
    /**
     * @param ?DriverResource $replacedBy
     */
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        #[Groups('driver')]
        public ?string $uuid = null,
        #[Assert\NotBlank]
        #[Groups('driver')]
        public ?string $firstName = null,
        #[Assert\NotBlank]
        #[Groups('driver')]
        public ?string $lastName = null,
        #[Assert\NotBlank]
        #[Groups('driver')]
        public ?string $name = null,
        #[Assert\CssColor(formats: 'hex_long')]
        #[Groups('driver')]
        public ?string $color = null,
        #[Assert\NotNull]
        #[Assert\PositiveOrZero]
        #[Groups('driver')]
        public ?int $minValue = null,
        #[Assert\NotNull]
        #[Groups('driver')]
        public bool $isReplacement = false,
        #[Assert\NotNull]
        #[Groups('driver')]
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?TeamResource $team = null,
        #[Groups('driver')]
        public ?\DateTimeImmutable $replacementDateStart = null,
        #[Groups('driver')]
        public ?\DateTimeImmutable $replacementDateEnd = null,
        #[Groups('driver')]
        public ?string $image = null,
        #[Assert\When(
            expression: 'this.isReplacement() === true',
            constraints: [new Assert\NotNull()]
        )]
        #[Groups('driver')]
        public $replacedBy = null, // @phpstan-ignore-line (If this variable is typed, Symfony detect a circular exception)
    ) {
    }

    public static function fromModel(DriverInterface $model): self
    {
        return new self(
            $model->getUuid(),
            $model->getFirstName(),
            $model->getLastName(),
            $model->getName(),
            $model->getColor(),
            $model->getMinValue(),
            $model->isReplacement(),
            TeamResource::fromModel($model->getTeam()),
            $model->getReplacementDateStart(),
            $model->getReplacementDateEnd(),
            $model->getRelativeImagePath(),
            $model->getReplacedBy() ? self::fromModel($model->getReplacedBy()) : null,
        );
    }
}
