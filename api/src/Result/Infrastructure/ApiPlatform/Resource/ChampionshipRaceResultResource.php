<?php

declare(strict_types=1);

namespace App\Result\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Duel\Infrastructure\ApiPlatform\Resource\DuelResource;
use App\Result\Infrastructure\ApiPlatform\State\Provider\ChampionshipRaceResultProvider;
use App\Strategy\Infrastructure\ApiPlatform\Resource\StrategyResource;

#[ApiResource(
    shortName: 'ChampionRaceResult',
    operations: [
        new GetCollection(
            uriTemplate: 'championships/{championshipUuid}/race/{raceUuid}/result',
            uriVariables: [
                'championshipUuid' => 'string',
                'raceUuid' => 'string',
            ],
            provider: ChampionshipRaceResultProvider::class,
        ),
    ],
    normalizationContext: [
        'skip_null_values' => false,
    ],
)]
class ChampionshipRaceResultResource
{
    /**
     * @param StrategyResource[]|null $strategies
     * @param DuelResource[]|null     $duels
     */
    public function __construct(
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?array $strategies = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?array $duels = null,
    ) {
    }
}
