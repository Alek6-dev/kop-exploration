<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\SeasonGame\Application\Query\GetSeasonParticipation\GetSeasonParticipationQuery;
use App\SeasonGame\Infrastructure\ApiPlatform\Resource\SeasonGPStrategyResource;
use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonGPStrategyRepository;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class SeasonGPStrategyProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Security $security,
        private DoctrineSeasonGPStrategyRepository $strategyRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?SeasonGPStrategyResource
    {
        /** @var UserVisitor $user */
        $user = $this->security->getUser();

        $participation = $this->queryBus->ask(new GetSeasonParticipationQuery($user));

        if (!$participation) {
            return null;
        }

        $strategy = $this->strategyRepository->findByParticipationAndRace($participation, $uriVariables['raceUuid']);

        if (!$strategy) {
            return null;
        }

        return SeasonGPStrategyResource::fromModel($strategy);
    }
}
