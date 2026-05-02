<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use App\SeasonGame\Application\Dto\ApplySeasonBonusDto;
use App\SeasonGame\Application\Dto\SaveSeasonGPStrategyDto;
use App\SeasonGame\Infrastructure\ApiPlatform\State\Processor\ApplySeasonBonusProcessor;
use App\SeasonGame\Infrastructure\ApiPlatform\State\Processor\SaveSeasonGPStrategyProcessor;
use App\SeasonGame\Infrastructure\ApiPlatform\State\Provider\SeasonGPStrategyProvider;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonGPStrategy;

#[ApiResource(
    shortName: 'SeasonGPStrategy',
    operations: [
        new Get(
            uriTemplate: '/season-game/strategy/{raceUuid}',
            uriVariables: [
                'raceUuid' => new Link(fromClass: self::class, fromProperty: 'raceUuid'),
            ],
            provider: SeasonGPStrategyProvider::class,
            openapi: new Operation(
                summary: 'Récupérer la stratégie pour un GP',
                tags: ['Season Game'],
            ),
        ),
        new Post(
            uriTemplate: '/season-game/strategy/{raceUuid}',
            uriVariables: ['raceUuid' => 'string'],
            input: SaveSeasonGPStrategyDto::class,
            output: false,
            processor: SaveSeasonGPStrategyProcessor::class,
            status: 204,
            openapi: new Operation(
                summary: 'Sauvegarder la stratégie pour un GP',
                tags: ['Season Game'],
            ),
        ),
        new Post(
            uriTemplate: '/season-game/strategy/{raceUuid}/bonus',
            uriVariables: ['raceUuid' => 'string'],
            input: ApplySeasonBonusDto::class,
            processor: ApplySeasonBonusProcessor::class,
            openapi: new Operation(
                summary: 'Acheter et appliquer un bonus sur un GP',
                tags: ['Season Game'],
            ),
        ),
    ],
    normalizationContext: ['skip_null_values' => false],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class SeasonGPStrategyResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: false)]
        public ?string $uuid = null,
        #[ApiProperty(identifier: true)]
        public ?string $raceUuid = null,
        public ?string $raceName = null,
        public ?array $driver1 = null,
        public ?array $driver2 = null,
        public ?array $team = null,
        public ?int $points = null,
        public ?bool $locked = null,
        public ?array $bonuses = null,
        public ?string $limitStrategyDate = null,
        public ?string $userPseudo = null,
        public ?string $userUuid = null,
    ) {
    }

    public static function fromModel(SeasonGPStrategy $model): self
    {
        return new self(
            uuid: $model->getUuid(),
            raceUuid: $model->getRaceUuid(),
            raceName: $model->getRace()?->getName(),
            driver1: $model->getDriver1() ? [
                'uuid' => $model->getDriver1()->getUuid(),
                'driverUuid' => $model->getDriver1()->getDriver()->getUuid(),
                'name' => $model->getDriver1()->getDriver()->getName(),
            ] : null,
            driver2: $model->getDriver2() ? [
                'uuid' => $model->getDriver2()->getUuid(),
                'driverUuid' => $model->getDriver2()->getDriver()->getUuid(),
                'name' => $model->getDriver2()->getDriver()->getName(),
            ] : null,
            team: $model->getTeam() ? [
                'uuid' => $model->getTeam()->getUuid(),
                'teamUuid' => $model->getTeam()->getTeam()->getUuid(),
                'name' => $model->getTeam()->getTeam()->getName(),
            ] : null,
            points: $model->getPoints(),
            locked: $model->isLocked(),
            bonuses: $model->getBonusUsages()->map(fn ($b) => [
                'type' => $b->getBonusType()->value,
                'label' => $b->getBonusType()->label(),
                'pricePaid' => $b->getPricePaid(),
            ])->toArray(),
            userPseudo: $model->getParticipation()?->getUser()?->getPseudo(),
            userUuid: $model->getParticipation()?->getUser()?->getUuid(),
        );
    }
}
