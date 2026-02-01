<?php

declare(strict_types=1);

namespace App\Driver\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Championship\Application\Query\Get\GetChampionshipQuery;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Exception\ChampionshipException;
use App\Driver\Application\Query\GetAvailableDriversOnChampionshipCollection\GetAvailableDriversOnChampionshipQuery;
use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Domain\Repository\DriverRepositoryInterface;
use App\Driver\Infrastructure\ApiPlatform\Resource\DriverResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\ApiPlatform\State\Paginator;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<Paginator|DriverResource[]>
 */
final readonly class DriversAvailableByChampionshipCollectionProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Pagination $pagination,
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Paginator|array
    {
        /** @var string $uuid */
        $uuid = $uriVariables['championshipUuid'];

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();
        $championship = $this->queryBus->ask(new GetChampionshipQuery(
            $uuid,
        ));
        if (!$championship->isPlayer($user)) {
            throw ChampionshipException::notAPlayer($user->getUuid());
        }

        if (ChampionshipStatusEnum::BID_IN_PROGRESS !== $championship->getStatus()) {
            throw ChampionshipException::notReady($championship->getUuid());
        }

        $offset = $limit = null;

        if ($this->pagination->isEnabled($operation, $context)) {
            $offset = $this->pagination->getPage($context);
            $limit = $this->pagination->getLimit($operation, $context);
        }

        /** @var DriverRepositoryInterface $models */
        $models = $this->queryBus->ask(new GetAvailableDriversOnChampionshipQuery(
            $championship,
            $offset,
            $limit,
        ));

        $resources = [];
        /** @var DriverInterface $model */
        foreach ($models as $model) {
            $resources[] = DriverResource::fromModel($model);
        }
        if (null !== $models->paginator()) {
            $paginator = $models->paginator();
            $resources = new Paginator(
                new \ArrayIterator($resources),
                (float) $paginator->getCurrentPage(),
                (float) $paginator->getItemsPerPage(),
                (float) $paginator->getLastPage(),
                (float) $paginator->getTotalItems(),
            );
        }

        return $resources;
    }
}
