<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Application\Dto\UserStatisticDto;
use App\User\Application\Query\Stat\CountChampionships\CountChampionshipsQuery;
use App\User\Application\Query\Stat\CountCosmeticsPossessed\CountCosmeticsPossessedQuery;
use App\User\Application\Query\Stat\CountDuels\CountDuelsQuery;
use App\User\Application\Query\Stat\CountStrategies\CountStrategiesQuery;

/**
 * @implements ProviderInterface<UserStatisticDto>
 */
final readonly class UserStatisticCollectionProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): UserStatisticDto
    {
        /** @var string $uuid */
        $uuid = $uriVariables['uuid'];

        return new UserStatisticDto(
            $this->queryBus->ask(new CountChampionshipsQuery($uuid, 1)),
            $this->queryBus->ask(new CountChampionshipsQuery($uuid)),
            $this->queryBus->ask(new CountDuelsQuery($uuid, true)),
            $this->queryBus->ask(new CountDuelsQuery($uuid, false)),
            $this->queryBus->ask(new CountCosmeticsPossessedQuery($uuid)),
            $this->queryBus->ask(new CountStrategiesQuery($uuid, 1)),
            $this->queryBus->ask(new CountStrategiesQuery($uuid)),
        );
    }
}
