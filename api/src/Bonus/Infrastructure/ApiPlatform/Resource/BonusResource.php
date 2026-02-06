<?php

declare(strict_types=1);

namespace App\Bonus\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
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
    description: 'Bonus achetables avec des crédits pour améliorer les performances',
    operations: [
        new GetCollection(
            uriTemplate: '/bonus',
            paginationEnabled: false,
            filters: [BonusFilter::class],
            provider: BonusCollectionProvider::class,
            openapi: new Operation(
                summary: 'Liste des bonus',
                description: 'Récupère tous les bonus disponibles, filtrables par type (RACE, DUEL, STRATEGY) et joker.',
                tags: ['Bonus'],
            ),
        ),
        new Post(
            uriTemplate: '/bonus/select',
            input: SelectBonusDto::class,
            output: false,
            processor: SelectBonusProcessor::class,
            openapi: new Operation(
                summary: 'Sélectionner un bonus',
                description: 'Applique un bonus sur une course, un duel ou une stratégie. Le coût en crédits sera déduit du portefeuille.',
                tags: ['Bonus'],
                requestBody: new RequestBody(
                    description: 'Détails du bonus à appliquer',
                    required: true,
                ),
                responses: [
                    '204' => [
                        'description' => 'Bonus appliqué avec succès',
                    ],
                    '400' => [
                        'description' => 'Bonus invalide ou crédits insuffisants',
                    ],
                    '409' => [
                        'description' => 'Bonus déjà appliqué ou limite atteinte',
                    ],
                ],
            ),
        ),
        new Post(
            uriTemplate: '/bonus/unselect',
            input: UnselectBonusDto::class,
            output: false,
            processor: UnselectBonusProcessor::class,
            openapi: new Operation(
                summary: 'Retirer un bonus',
                description: 'Retire un bonus précédemment appliqué. Les crédits seront remboursés.',
                tags: ['Bonus'],
                requestBody: new RequestBody(
                    description: 'Identifiant de l\'application de bonus à retirer',
                    required: true,
                ),
                responses: [
                    '204' => [
                        'description' => 'Bonus retiré avec succès',
                    ],
                    '404' => [
                        'description' => 'Application de bonus non trouvée',
                    ],
                ],
            ),
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
        #[ApiProperty(
            identifier: true,
            description: 'Identifiant unique du bonus',
            example: 'b1c2d3e4-f5a6-7b8c-9d0e-1f2a3b4c5d6e',
        )]
        public string $uuid,
        #[ApiProperty(
            description: 'Nom du bonus',
            example: 'Turbo Boost',
        )]
        public string $name,
        #[ApiProperty(
            description: 'Description détaillée de l\'effet du bonus',
            example: 'Augmente les points marqués par le pilote de 20%',
        )]
        public string $description,
        #[ApiProperty(
            description: 'Exemple d\'utilisation du bonus',
            example: 'Si votre pilote marque 25 points, vous obtiendrez 30 points',
        )]
        public string $example,
        #[ApiProperty(
            description: 'Icône du bonus',
            example: 'rocket',
        )]
        public string $icon,
        #[ApiProperty(
            description: 'Nombre de fois que ce bonus peut être cumulé (null = illimité)',
            example: 3,
        )]
        public ?int $cumulativeTimes,
        #[ApiProperty(
            description: 'Prix du bonus en crédits',
            example: 5000000,
        )]
        public int $price,
        #[ApiProperty(
            description: 'Type de bonus (RACE, DUEL, STRATEGY)',
            example: 'RACE',
        )]
        public BonusTypeEnum $type,
        #[ApiProperty(
            description: 'Cible du bonus (DRIVER, TEAM, PLAYER)',
            example: 'DRIVER',
        )]
        public TargetTypeEnum $targetType,
        #[ApiProperty(
            description: 'Opération appliquée (MULTIPLY, ADD, SUBTRACT)',
            example: 'MULTIPLY',
        )]
        public OperationEnum $operation,
        #[ApiProperty(
            description: 'Attribut affecté (POINTS, POSITION, etc.)',
            example: 'POINTS',
        )]
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
