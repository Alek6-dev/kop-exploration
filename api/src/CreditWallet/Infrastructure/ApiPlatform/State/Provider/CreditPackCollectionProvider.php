<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Championship\Infrastructure\ApiPlatform\Resource\ChampionshipResource;
use App\CreditWallet\Application\Query\Collection\GetCreditPacksQuery;
use App\CreditWallet\Domain\Model\CreditPackInterface;
use App\CreditWallet\Domain\Repository\CreditPackRepositoryInterface;
use App\CreditWallet\Infrastructure\ApiPlatform\Resource\CreditPackResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\ApiPlatform\State\Paginator;

/**
 * @implements ProviderInterface<Paginator|array<ChampionshipResource>>
 */
final readonly class CreditPackCollectionProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Pagination $pagination,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Paginator|array
    {
        $offset = $limit = null;

        if ($this->pagination->isEnabled($operation, $context)) {
            $offset = $this->pagination->getPage($context);
            $limit = $this->pagination->getLimit($operation, $context);
        }

        /** @var CreditPackRepositoryInterface $models */
        $models = $this->queryBus->ask(new GetCreditPacksQuery(
            $offset,
            $limit,
        ));

        $resources = [];
        /** @var CreditPackInterface $model */
        foreach ($models as $model) {
            $resources[] = CreditPackResource::fromModel($model);
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
