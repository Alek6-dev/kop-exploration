<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use App\SeasonGame\Application\Dto\CreateSeasonRosterDto;
use App\SeasonGame\Infrastructure\ApiPlatform\State\Processor\CreateSeasonRosterProcessor;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonRoster;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonRosterDriver;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonRosterTeam;

#[ApiResource(
    shortName: 'SeasonRoster',
    operations: [
        new Post(
            uriTemplate: '/season-game/roster',
            input: CreateSeasonRosterDto::class,
            processor: CreateSeasonRosterProcessor::class,
            openapi: new Operation(
                summary: 'Valider la composition d\'équipe',
                tags: ['Season Game'],
            ),
        ),
    ],
    normalizationContext: ['skip_null_values' => false],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class SeasonRosterResource
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        public ?int $budgetSpent = null,
        public ?string $validatedAt = null,
        public ?array $drivers = null,
        public ?array $teams = null,
    ) {
    }

    public static function fromModel(SeasonRoster $model): self
    {
        return new self(
            uuid: $model->getUuid(),
            budgetSpent: $model->getBudgetSpent(),
            validatedAt: $model->getValidatedAt()?->format('Y-m-d H:i:s'),
            drivers: $model->getDrivers()->map(fn (SeasonRosterDriver $d) => [
                'uuid' => $d->getUuid(),
                'driverUuid' => $d->getDriver()->getUuid(),
                'driverName' => $d->getDriver()->getName(),
                'driverImage' => $d->getDriver()->getRelativeImagePath(),
                'teamName' => $d->getDriver()->getTeam()?->getName(),
                'teamColor' => $d->getDriver()->getColor(),
                'purchasePrice' => $d->getPurchasePrice(),
                'maxUsages' => $d->getMaxUsages(),
                'usagesLeft' => $d->getUsagesLeft(),
            ])->toArray(),
            teams: $model->getTeams()->map(fn (SeasonRosterTeam $t) => [
                'uuid' => $t->getUuid(),
                'teamUuid' => $t->getTeam()->getUuid(),
                'teamName' => $t->getTeam()->getName(),
                'teamColor' => $t->getTeam()->getColor(),
                'teamImage' => $t->getTeam()->getRelativeImagePath(),
                'purchasePrice' => $t->getPurchasePrice(),
                'maxUsages' => $t->getMaxUsages(),
                'usagesLeft' => $t->getUsagesLeft(),
            ])->toArray(),
        );
    }
}
