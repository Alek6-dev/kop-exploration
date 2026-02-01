<?php

declare(strict_types=1);

namespace App\Team\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Infrastructure\ApiPlatform\State\Provider\TeamsAvailableByChampionshipCollectionProvider;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ApiResource(
    shortName: 'Team',
    operations: [
        new GetCollection(
            uriTemplate: '/championships/{championshipUuid}/teams-available',
            uriVariables: [
                'championshipUuid' => 'string',
            ],
            paginationEnabled: false,
            provider: TeamsAvailableByChampionshipCollectionProvider::class,
        ),
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
#[Vich\Uploadable]
class TeamResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[Assert\NotBlank]
        #[Groups('driver')]
        public ?string $name = null,
        #[Assert\CssColor(formats: 'hex_long')]
        public ?string $color = null,
        #[Assert\NotNull]
        #[Assert\PositiveOrZero]
        public ?int $minValue = null,
        public ?string $image = null
    ) {
    }

    public static function fromModel(TeamInterface $model): self
    {
        return new self(
            $model->getUuid(),
            $model->getName(),
            $model->getColor(),
            $model->getMinValue(),
            $model->getRelativeImagePath(),
        );
    }
}
