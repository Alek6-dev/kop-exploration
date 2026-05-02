<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use App\SeasonGame\Application\Dto\EnrollInSeasonDto;
use App\SeasonGame\Infrastructure\ApiPlatform\State\Processor\EnrollInSeasonProcessor;
use App\SeasonGame\Infrastructure\ApiPlatform\State\Provider\SeasonParticipationProvider;
use App\SeasonGame\Infrastructure\ApiPlatform\State\Provider\SeasonRankingProvider;
use App\SeasonGame\Infrastructure\ApiPlatform\State\Provider\SeasonGPRankingProvider;
use App\SeasonGame\Infrastructure\ApiPlatform\State\Provider\PreviousSeasonsProvider;
use App\SeasonGame\Infrastructure\ApiPlatform\State\Provider\SeasonAvailableDriversProvider;
use App\SeasonGame\Infrastructure\ApiPlatform\State\Provider\SeasonAvailableTeamsProvider;
use App\SeasonGame\Infrastructure\ApiPlatform\State\Provider\SeasonScoredRacesProvider;
use App\Season\Infrastructure\Doctrine\Entity\SeasonRace;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonParticipation;

#[ApiResource(
    shortName: 'SeasonGame',
    operations: [
        new Get(
            uriTemplate: '/season-game/me',
            provider: SeasonParticipationProvider::class,
            openapi: new Operation(
                summary: 'Récupérer la participation en cours',
                tags: ['Season Game'],
            ),
        ),
        new Post(
            uriTemplate: '/season-game/enroll',
            input: EnrollInSeasonDto::class,
            processor: EnrollInSeasonProcessor::class,
            openapi: new Operation(
                summary: 'S\'inscrire au mode Saison',
                tags: ['Season Game'],
            ),
        ),
        new GetCollection(
            uriTemplate: '/season-game/ranking',
            provider: SeasonRankingProvider::class,
            openapi: new Operation(
                summary: 'Classement saison global',
                tags: ['Season Game'],
            ),
        ),
        new GetCollection(
            uriTemplate: '/season-game/ranking/gp/{raceUuid}',
            uriVariables: ['raceUuid' => 'string'],
            provider: SeasonGPRankingProvider::class,
            openapi: new Operation(
                summary: 'Classement d\'un GP',
                tags: ['Season Game'],
            ),
        ),
        new GetCollection(
            uriTemplate: '/season-game/previous-seasons',
            provider: PreviousSeasonsProvider::class,
            openapi: new Operation(
                summary: 'Palmarès — saisons précédentes',
                tags: ['Season Game'],
            ),
        ),
        new GetCollection(
            uriTemplate: '/season-game/available-drivers',
            provider: SeasonAvailableDriversProvider::class,
            paginationEnabled: false,
            openapi: new Operation(
                summary: 'Pilotes disponibles pour la composition saison',
                tags: ['Season Game'],
            ),
        ),
        new GetCollection(
            uriTemplate: '/season-game/available-teams',
            provider: SeasonAvailableTeamsProvider::class,
            paginationEnabled: false,
            openapi: new Operation(
                summary: 'Écuries disponibles pour la composition saison',
                tags: ['Season Game'],
            ),
        ),
        new GetCollection(
            uriTemplate: '/season-game/scored-races',
            provider: SeasonScoredRacesProvider::class,
            paginationEnabled: false,
            openapi: new Operation(
                summary: 'GPs scorés dans la saison active',
                tags: ['Season Game'],
            ),
        ),
    ],
    normalizationContext: ['skip_null_values' => false],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class SeasonParticipationResource
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        public ?int $totalPoints = null,
        public ?int $walletBalance = null,
        public ?string $enrolledAt = null,
        public ?bool $hasRoster = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?SeasonRosterResource $roster = null,
        public ?string $seasonName = null,
        public ?bool $seasonActive = null,
        public ?array $nextRace = null,
        public ?string $userPseudo = null,
        public ?string $userUuid = null,
    ) {
    }

    public static function fromModel(SeasonParticipation $model, ?SeasonRace $nextRace = null): self
    {
        return new self(
            uuid: $model->getUuid(),
            totalPoints: $model->getTotalPoints(),
            walletBalance: $model->getWalletBalance(),
            enrolledAt: $model->getEnrolledAt()?->format('Y-m-d H:i:s'),
            hasRoster: $model->hasRoster(),
            roster: $model->hasRoster() ? SeasonRosterResource::fromModel($model->getRoster()) : null,
            seasonName: $model->getSeason()?->getName(),
            seasonActive: $model->getSeason()?->isActive(),
            userPseudo: $model->getUser()?->getPseudo(),
            userUuid: $model->getUser()?->getUuid(),
            nextRace: $nextRace ? [
                'uuid' => $nextRace->getRace()->getUuid(),
                'name' => $nextRace->getRace()->getName(),
                'date' => $nextRace->getDate()?->format('Y-m-d H:i:s'),
                'limitStrategyDate' => $nextRace->getLimitStrategyDate()?->format('Y-m-d H:i:s'),
                'isSprintWeekend' => null !== $nextRace->getSprintDate(),
            ] : null,
        );
    }
}
