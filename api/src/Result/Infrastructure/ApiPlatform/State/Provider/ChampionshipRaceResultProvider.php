<?php

declare(strict_types=1);

namespace App\Result\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Championship\Application\Query\Get\GetChampionshipQuery;
use App\Championship\Domain\Exception\ChampionshipException;
use App\Championship\Domain\Exception\ChampionshipRaceException;
use App\Championship\Domain\Model\ChampionshipRaceInterface;
use App\Duel\Application\Query\GetCollection\GetDuelCollectionQuery;
use App\Duel\Domain\Model\DuelInterface;
use App\Duel\Domain\Repository\DuelRepositoryInterface;
use App\Duel\Infrastructure\ApiPlatform\Resource\DuelResource;
use App\Race\Application\Query\Get\GetRaceQuery;
use App\Result\Infrastructure\ApiPlatform\Resource\ChampionshipRaceResultResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\Strategy\Application\Query\GetCollection\GetStrategyCollectionQuery;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Domain\Repository\StrategyRepositoryInterface;
use App\Strategy\Infrastructure\ApiPlatform\Resource\StrategyResource;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<ChampionshipRaceResultResource>
 */
final readonly class ChampionshipRaceResultProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ChampionshipRaceResultResource
    {
        /** @var string $uuid */
        $uuid = $uriVariables['championshipUuid'];
        /** @var string $raceUuid */
        $raceUuid = $uriVariables['raceUuid'];

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();
        $championship = $this->queryBus->ask(new GetChampionshipQuery(
            $uuid,
        ));

        if (!$championship->isPlayer($user)) {
            throw ChampionshipException::notAPlayer($user->getUuid());
        }

        $race = $this->queryBus->ask(new GetRaceQuery(
            $raceUuid
        ));
        $attached = $championship->getChampionshipRaces()->exists(fn (int $key, ChampionshipRaceInterface $championshipRace) => $race === $championshipRace->getRace());
        if (!$attached) {
            ChampionshipRaceException::raceNotAttachedToChampionship($raceUuid, $uuid);
        }

        /** @var StrategyRepositoryInterface $strategies */
        $strategies = $this->queryBus->ask(new GetStrategyCollectionQuery(
            $championship,
            $race,
        ));

        /** @var DuelRepositoryInterface $duels */
        $duels = $this->queryBus->ask(new GetDuelCollectionQuery(
            $championship,
            $race,
        ));

        return new ChampionshipRaceResultResource(
            array_map(
                fn (StrategyInterface $strategy) => StrategyResource::fromModel($strategy, $race),
                $strategies->getResult(),
            ),
            array_map(
                fn (DuelInterface $duel) => DuelResource::fromModel($duel, $race, $championship->getPlayer($user)),
                $duels->getResult(),
            ),
        );
    }
}
