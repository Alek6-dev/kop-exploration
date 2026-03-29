<?php

declare(strict_types=1);

namespace App\Team\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Infrastructure\ApiPlatform\State\Provider\TeamsAvailableByChampionshipCollectionProvider;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ApiResource(
    shortName: 'Team',
    description: 'Écuries disponibles pour les enchères dans un championnat',
    operations: [
        new GetCollection(
            uriTemplate: '/championships/{championshipUuid}/teams-available',
            uriVariables: [
                'championshipUuid' => 'string',
            ],
            paginationEnabled: false,
            provider: TeamsAvailableByChampionshipCollectionProvider::class,
            openapi: new Operation(
                summary: 'Liste des écuries disponibles',
                description: 'Récupère toutes les écuries disponibles pour les enchères dans un championnat spécifique.',
                tags: ['Écuries'],
            ),
        ),
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
#[Vich\Uploadable]
class TeamResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(
            identifier: true,
            description: 'Identifiant unique de l\'écurie',
            example: '7f8e9d0c-1a2b-3c4d-5e6f-7a8b9c0d1e2f',
        )]
        public ?string $uuid = null,
        #[Assert\NotBlank]
        #[Groups('driver')]
        #[ApiProperty(
            description: 'Nom de l\'écurie',
            example: 'Red Bull Racing',
        )]
        public ?string $name = null,
        #[Assert\CssColor(formats: 'hex_long')]
        #[ApiProperty(
            description: 'Couleur de l\'écurie (format hexadécimal)',
            example: '#1E41FF',
        )]
        public ?string $color = null,
        #[Assert\NotNull]
        #[Assert\PositiveOrZero]
        #[ApiProperty(
            description: 'Valeur minimale d\'enchère pour cette écurie en crédits',
            example: 20000000,
        )]
        public ?int $minValue = null,
        #[ApiProperty(
            description: 'Chemin relatif vers le logo de l\'écurie',
            example: '/uploads/teams/redbull.png',
        )]
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
