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
use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Model\ChampionshipRaceInterface;
use App\Championship\Infrastructure\ApiPlatform\OpenApi\ChampionshipFilter;
use App\Championship\Infrastructure\ApiPlatform\State\Processor\CreateChampionshipProcessor;
use App\Championship\Infrastructure\ApiPlatform\State\Processor\DeleteChampionshipProcessor;
use App\Championship\Infrastructure\ApiPlatform\State\Processor\JoinChampionshipProcessor;
use App\Championship\Infrastructure\ApiPlatform\State\Provider\ChampionshipCollectionProvider;
use App\Championship\Infrastructure\ApiPlatform\State\Provider\ChampionshipItemAsCreatorProvider;
use App\Championship\Infrastructure\ApiPlatform\State\Provider\ChampionshipItemProvider;
use App\Season\Domain\Model\SeasonRaceInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'ChampionshipRace',
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
            uriTemplate: 'championships/join/{invitationCode}',
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
            processor: DeleteChampionshipProcessor::class,
        ),
    ],
    normalizationContext: [
        'skip_null_values' => false,
        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s',
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class ChampionshipRaceResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[Assert\NotBlank]
        public ?string $name = null,
        public ?string $flagUrl = null,
        public ?\DateTimeImmutable $date = null,
        public ?\DateTimeImmutable $limitStrategyDate = null,
        public ?ChampionshipRaceStatusEnum $status = null,
    ) {
    }

    public static function fromModel(ChampionshipRaceInterface $model): self
    {
        /** @var ?SeasonRaceInterface $seasonRace */
        $seasonRace = $model->getChampionship()->getSeason()->getSeasonRaces()->findFirst(fn (int $key, SeasonRaceInterface $seasonRace) => $seasonRace->getRace() === $model->getRace());

        return new self(
            $model->getRace()->getUuid(),
            $model->getRace()->getName(),
            $model->getRace()->getCountry()->getFlag4x3Link(),
            $seasonRace?->getDate(),
            $seasonRace?->getLimitStrategyDate(),
            $model->getStatus(),
        );
    }
}
