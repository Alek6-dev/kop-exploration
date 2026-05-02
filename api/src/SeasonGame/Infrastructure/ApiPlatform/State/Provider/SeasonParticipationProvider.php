<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Season\Infrastructure\Doctrine\Entity\SeasonRace;
use App\SeasonGame\Application\Query\GetSeasonParticipation\GetSeasonParticipationQuery;
use App\SeasonGame\Infrastructure\ApiPlatform\Resource\SeasonParticipationResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class SeasonParticipationProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Security $security,
        private EntityManagerInterface $em,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?SeasonParticipationResource
    {
        /** @var UserVisitor $user */
        $user = $this->security->getUser();

        $participation = $this->queryBus->ask(new GetSeasonParticipationQuery($user));

        if (!$participation) {
            return null;
        }

        $nextRace = $this->findNextRace($participation->getSeason());

        return SeasonParticipationResource::fromModel($participation, $nextRace);
    }

    private function findNextRace(object $season): ?SeasonRace
    {
        return $this->em->createQuery(
            'SELECT sr FROM App\Season\Infrastructure\Doctrine\Entity\SeasonRace sr
             WHERE sr.season = :season AND sr.limitStrategyDate > :now
             ORDER BY sr.date ASC'
        )
            ->setParameter('season', $season)
            ->setParameter('now', new \DateTimeImmutable())
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}
