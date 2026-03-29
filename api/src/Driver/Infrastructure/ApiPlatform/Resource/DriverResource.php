<?php

declare(strict_types=1);

namespace App\Driver\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Infrastructure\ApiPlatform\State\Provider\DriversAvailableByChampionshipCollectionProvider;
use App\Team\Infrastructure\ApiPlatform\Resource\TeamResource;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ApiResource(
    shortName: 'Driver',
    description: 'Pilotes disponibles pour les enchères dans un championnat',
    operations: [
        new GetCollection(
            uriTemplate: '/championships/{championshipUuid}/drivers-available',
            uriVariables: [
                'championshipUuid' => 'string',
            ],
            openapi: new Operation(
                tags: ['Pilotes'],
                summary: 'Liste des pilotes disponibles',
                description: 'Récupère tous les pilotes disponibles pour les enchères dans un championnat spécifique. Inclut les informations sur l\'écurie, la valeur minimale et les remplaçants.',
            ),
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
        #[ApiProperty(
            description: 'Identifiant unique du pilote',
            identifier: true,
            example: '550e8400-e29b-41d4-a716-446655440000',
        )]
        #[Groups('driver')]
        public ?string $uuid = null,
        #[Assert\NotBlank]
        #[ApiProperty(
            description: 'Prénom du pilote',
            example: 'Max',
        )]
        #[Groups('driver')]
        public ?string $firstName = null,
        #[Assert\NotBlank]
        #[ApiProperty(
            description: 'Nom de famille du pilote',
            example: 'Verstappen',
        )]
        #[Groups('driver')]
        public ?string $lastName = null,
        #[Assert\NotBlank]
        #[ApiProperty(
            description: 'Nom complet du pilote',
            example: 'Max Verstappen',
        )]
        #[Groups('driver')]
        public ?string $name = null,
        #[Assert\CssColor(formats: 'hex_long')]
        #[ApiProperty(
            description: 'Couleur associée au pilote (format hexadécimal)',
            example: '#0600EF',
        )]
        #[Groups('driver')]
        public ?string $color = null,
        #[Assert\NotNull]
        #[Assert\PositiveOrZero]
        #[ApiProperty(
            description: 'Valeur minimale d\'enchère pour ce pilote en crédits',
            example: 15000000,
        )]
        #[Groups('driver')]
        public ?int $minValue = null,
        #[Assert\NotNull]
        #[ApiProperty(
            description: 'Indique si le pilote est un remplaçant',
            example: false,
        )]
        #[Groups('driver')]
        public bool $isReplacement = false,
        #[Assert\NotNull]
        #[Groups('driver')]
        #[ApiProperty(
            readableLink: true,
            writableLink: false,
            description: 'Écurie du pilote',
        )]
        public ?TeamResource $team = null,
        #[ApiProperty(
            description: 'Date de début du remplacement (si pilote remplaçant)',
            example: '2024-05-26 00:00:00',
        )]
        #[Groups('driver')]
        public ?\DateTimeImmutable $replacementDateStart = null,
        #[ApiProperty(
            description: 'Date de fin du remplacement (si pilote remplaçant)',
            example: '2024-06-15 23:59:59',
        )]
        #[Groups('driver')]
        public ?\DateTimeImmutable $replacementDateEnd = null,
        #[ApiProperty(
            description: 'Chemin relatif vers l\'image du pilote',
            example: '/uploads/drivers/verstappen.png',
        )]
        #[Groups('driver')]
        public ?string $image = null,
        #[Assert\When(
            expression: 'this.isReplacement() === true',
            constraints: [new Assert\NotNull()]
        )]
        #[ApiProperty(
            description: 'Pilote remplacé (si ce pilote est un remplaçant)',
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
