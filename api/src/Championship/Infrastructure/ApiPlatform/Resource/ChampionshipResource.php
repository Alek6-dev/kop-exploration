<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
use App\Championship\Application\Dto\CreateChampionshipDto;
use App\Championship\Application\Dto\JoinChampionshipDto;
use App\Championship\Domain\Enum\ChampionshipNumberPlayerEnum;
use App\Championship\Domain\Enum\ChampionshipNumberRaceEnum;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Model\ChampionshipRaceInterface;
use App\Championship\Infrastructure\ApiPlatform\OpenApi\ChampionshipFilter;
use App\Championship\Infrastructure\ApiPlatform\State\Processor\CreateChampionshipProcessor;
use App\Championship\Infrastructure\ApiPlatform\State\Processor\DeleteChampionshipProcessor;
use App\Championship\Infrastructure\ApiPlatform\State\Processor\JoinChampionshipProcessor;
use App\Championship\Infrastructure\ApiPlatform\State\Processor\StartChampionshipProcessor;
use App\Championship\Infrastructure\ApiPlatform\State\Provider\ChampionshipCollectionProvider;
use App\Championship\Infrastructure\ApiPlatform\State\Provider\ChampionshipItemAsCreatorProvider;
use App\Championship\Infrastructure\ApiPlatform\State\Provider\ChampionshipItemProvider;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Infrastructure\ApiPlatform\Resource\PlayerResource;
use App\Season\Infrastructure\ApiPlatform\Resource\SeasonResource;
use App\User\Infrastructure\ApiPlatform\Resource\UserResource;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Championship',
    description: 'Championnats de fantasy league motorsport',
    operations: [
        new Get(
            paginationEnabled: false,
            provider: ChampionshipItemProvider::class,
            openapi: new Operation(
                summary: 'Détails d\'un championnat',
                description: 'Récupère les informations complètes d\'un championnat incluant les joueurs, les courses et le classement.',
                tags: ['Championnats'],
                responses: [
                    '200' => [
                        'description' => 'Détails du championnat',
                    ],
                    '404' => [
                        'description' => 'Championnat non trouvé',
                    ],
                ],
            ),
        ),
        new GetCollection(
            filters: [ChampionshipFilter::class],
            provider: ChampionshipCollectionProvider::class,
            openapi: new Operation(
                summary: 'Liste des championnats',
                description: 'Récupère la liste des championnats auxquels l\'utilisateur participe. Peut être filtrée par statut (actif/inactif).',
                tags: ['Championnats'],
            ),
        ),
        new Post(
            input: CreateChampionshipDto::class,
            processor: CreateChampionshipProcessor::class,
            openapi: new Operation(
                summary: 'Créer un championnat',
                description: 'Crée un nouveau championnat avec les paramètres spécifiés. L\'utilisateur devient le créateur et le premier joueur du championnat.',
                tags: ['Championnats'],
                requestBody: new RequestBody(
                    description: 'Paramètres du championnat à créer',
                    required: true,
                ),
                responses: [
                    '201' => [
                        'description' => 'Championnat créé avec succès',
                    ],
                    '400' => [
                        'description' => 'Données invalides',
                        'content' => [
                            'application/ld+json' => [
                                'example' => [
                                    '@type' => 'ConstraintViolationList',
                                    'violations' => [
                                        [
                                            'propertyPath' => 'name',
                                            'message' => 'Le nom doit contenir entre 3 et 30 caractères',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ),
        ),
        new Post(
            uriTemplate: '/championships/join/{invitationCode}',
            uriVariables: [
                'invitationCode' => 'string',
            ],
            input: JoinChampionshipDto::class,
            processor: JoinChampionshipProcessor::class,
            openapi: new Operation(
                summary: 'Rejoindre un championnat',
                description: 'Permet à un joueur de rejoindre un championnat existant via un code d\'invitation.',
                tags: ['Championnats'],
                requestBody: new RequestBody(
                    description: 'Nom du joueur pour ce championnat',
                    required: true,
                ),
                responses: [
                    '201' => [
                        'description' => 'Joueur ajouté au championnat',
                    ],
                    '400' => [
                        'description' => 'Code d\'invitation invalide ou championnat complet',
                    ],
                    '409' => [
                        'description' => 'L\'utilisateur participe déjà à ce championnat',
                    ],
                ],
            ),
        ),
        new Post(
            uriTemplate: '/championships/cancel/{uuid}',
            input: false,
            output: false,
            provider: ChampionshipItemAsCreatorProvider::class,
            processor: DeleteChampionshipProcessor::class,
            openapi: new Operation(
                summary: 'Annuler un championnat',
                description: 'Annule un championnat avant son démarrage. Seul le créateur peut effectuer cette action.',
                tags: ['Championnats'],
                responses: [
                    '204' => [
                        'description' => 'Championnat annulé avec succès',
                    ],
                    '403' => [
                        'description' => 'Vous n\'êtes pas le créateur de ce championnat',
                    ],
                    '409' => [
                        'description' => 'Le championnat a déjà démarré et ne peut plus être annulé',
                    ],
                ],
            ),
        ),
        new Post(
            uriTemplate: '/championships/start/{uuid}',
            input: false,
            output: false,
            provider: ChampionshipItemAsCreatorProvider::class,
            processor: StartChampionshipProcessor::class,
            openapi: new Operation(
                summary: 'Démarrer un championnat',
                description: 'Démarre un championnat et ouvre la première période d\'enchères. Seul le créateur peut effectuer cette action.',
                tags: ['Championnats'],
                responses: [
                    '204' => [
                        'description' => 'Championnat démarré avec succès',
                    ],
                    '403' => [
                        'description' => 'Vous n\'êtes pas le créateur de ce championnat',
                    ],
                    '409' => [
                        'description' => 'Le championnat a déjà démarré ou conditions non remplies',
                    ],
                ],
            ),
        ),
    ],
    normalizationContext: [
        'skip_null_values' => false,
        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s',
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class ChampionshipResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(
            identifier: true,
            description: 'Identifiant unique du championnat',
            example: '123e4567-e89b-12d3-a456-426614174000',
        )]
        public ?string $uuid = null,
        #[Assert\Length(min: 3, max: 30)]
        #[ApiProperty(
            description: 'Nom du championnat',
            example: 'Championnat F1 2024',
        )]
        public ?string $name = null,
        #[Assert\NotNull]
        #[ApiProperty(
            description: 'Indique si le joker est activé pour ce championnat',
            example: true,
        )]
        public ?bool $jokerEnabled = null,
        #[Assert\Type(type: ChampionshipNumberRaceEnum::class)]
        #[ApiProperty(
            description: 'Nombre de courses dans le championnat',
            example: 'ALL',
        )]
        public ?ChampionshipNumberRaceEnum $numberOfRaces = null,
        #[Assert\Type(type: ChampionshipNumberPlayerEnum::class)]
        #[ApiProperty(
            description: 'Nombre maximum de joueurs',
            example: 'EIGHT',
        )]
        public ?ChampionshipNumberPlayerEnum $numberOfPlayers = null,
        #[Assert\NotBlank]
        #[ApiProperty(
            description: 'Code d\'invitation pour rejoindre le championnat',
            example: 'ABC123XYZ',
        )]
        public ?string $invitationCode = null,
        #[Assert\Type(type: ChampionshipStatusEnum::class)]
        #[ApiProperty(
            description: 'Statut actuel du championnat (CREATED, ACTIVE, FINISHED)',
            example: 'ACTIVE',
        )]
        public ?ChampionshipStatusEnum $status = ChampionshipStatusEnum::CREATED,
        #[ApiProperty(
            readableLink: true,
            writableLink: false,
            description: 'Liste des joueurs participants',
        )]
        public ?array $players = null,
        #[Assert\NotNull]
        #[ApiProperty(
            readableLink: true,
            writableLink: false,
            description: 'Saison associée au championnat',
        )]
        public ?SeasonResource $season = null,
        #[ApiProperty(
            description: 'Date limite d\'inscription au championnat',
            example: '2024-03-15 23:59:59',
        )]
        public ?\DateTimeImmutable $registrationEndDate = null,
        #[Assert\NotNull]
        #[ApiProperty(
            readableLink: true,
            writableLink: false,
            description: 'Utilisateur créateur du championnat',
        )]
        public ?UserResource $createdBy = null,
        #[ApiProperty(
            readableLink: true,
            writableLink: false,
            description: 'Liste des courses du championnat',
        )]
        public ?array $races = null,
        #[ApiProperty(
            description: 'Numéro de la manche en cours',
            example: 5,
        )]
        public ?int $currentRound = null,
        #[ApiProperty(
            description: 'Date de fin des enchères pour la manche en cours',
            example: '2024-05-20 18:00:00',
        )]
        public ?\DateTimeImmutable $currentRoundEndDate = null,
        #[ApiProperty(
            description: 'Nombre de joueurs ayant placé leurs enchères pour la manche en cours',
            example: 6,
        )]
        public int $countPlayersWithBidOnCurrentRound = 0,
        #[ApiProperty(
            description: 'Nombre de courses terminées',
            example: 4,
        )]
        public int $countRacesOver = 0,
    ) {
    }

    public static function fromModel(ChampionshipInterface $model): self
    {
        return new self(
            $model->getUuid(),
            $model->getName(),
            $model->hasJokerEnabled(),
            $model->getNumberOfRaces(),
            $model->getNumberOfPlayers(),
            $model->getInvitationCode(),
            $model->getStatus(),
            $model->getPlayers()->map(fn (PlayerInterface $player) => PlayerResource::fromModel($player))->toArray(),
            SeasonResource::fromModel($model->getSeason()),
            $model->getRegistrationEndDate(),
            UserResource::fromModel($model->getCreatedBy()),
            $model->getChampionshipRaces()->map(fn (ChampionshipRaceInterface $championshipRace) => ChampionshipRaceResource::fromModel($championshipRace))->toArray(),
            $model->getCurrentRound(),
            $model->getCurrentRoundEndDate(),
            $model->countPlayersWithBidOnCurrentRound(),
            $model->countRacesOver(),
        );
    }
}
