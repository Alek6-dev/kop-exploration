<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
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
    operations: [
        new Get(
            paginationEnabled: false,
            provider: ChampionshipItemProvider::class,
        ),
        new GetCollection(
            filters: [ChampionshipFilter::class],
            provider: ChampionshipCollectionProvider::class,
        ),
        new Post(
            input: CreateChampionshipDto::class,
            processor: CreateChampionshipProcessor::class,
        ),
        new Post(
            uriTemplate: '/championships/join/{invitationCode}',
            uriVariables: [
                'invitationCode' => 'string',
            ],
            input: JoinChampionshipDto::class,
            processor: JoinChampionshipProcessor::class,
        ),
        new Post(
            uriTemplate: '/championships/cancel/{uuid}',
            input: false,
            output: false,
            provider: ChampionshipItemAsCreatorProvider::class,
            processor: DeleteChampionshipProcessor::class,
        ),
        new Post(
            uriTemplate: '/championships/start/{uuid}',
            input: false,
            output: false,
            provider: ChampionshipItemAsCreatorProvider::class,
            processor: StartChampionshipProcessor::class,
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
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[Assert\Length(min: 3, max: 30)]
        public ?string $name = null,
        #[Assert\NotNull]
        public ?bool $jokerEnabled = null,
        #[Assert\Type(type: ChampionshipNumberRaceEnum::class)]
        public ?ChampionshipNumberRaceEnum $numberOfRaces = null,
        #[Assert\Type(type: ChampionshipNumberPlayerEnum::class)]
        public ?ChampionshipNumberPlayerEnum $numberOfPlayers = null,
        #[Assert\NotBlank]
        public ?string $invitationCode = null,
        #[Assert\Type(type: ChampionshipStatusEnum::class)]
        public ?ChampionshipStatusEnum $status = ChampionshipStatusEnum::CREATED,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?array $players = null,
        #[Assert\NotNull]
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?SeasonResource $season = null,
        public ?\DateTimeImmutable $registrationEndDate = null,
        #[Assert\NotNull]
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?UserResource $createdBy = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?array $races = null,
        public ?int $currentRound = null,
        public ?\DateTimeImmutable $currentRoundEndDate = null,
        public int $countPlayersWithBidOnCurrentRound = 0,
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
