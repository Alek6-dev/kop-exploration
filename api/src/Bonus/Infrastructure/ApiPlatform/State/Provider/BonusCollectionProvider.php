<?php

declare(strict_types=1);

namespace App\Bonus\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Bonus\Application\Query\Collection\GetBonusCollectionQuery;
use App\Bonus\Domain\Enum\BonusTypeEnum;
use App\Bonus\Domain\Model\BonusInterface;
use App\Bonus\Domain\Repository\BonusRepositoryInterface;
use App\Bonus\Infrastructure\ApiPlatform\OpenApi\BonusFilter;
use App\Bonus\Infrastructure\ApiPlatform\Resource\BonusResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\ApiPlatform\State\Paginator;

/**
 * @implements ProviderInterface<Paginator|array<BonusResource>>
 */
final readonly class BonusCollectionProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Pagination $pagination,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Paginator|array
    {
        $type = $context['filters'][BonusFilter::TYPE];
        $isJoker = (bool) $context['filters'][BonusFilter::JOKER];

        $offset = $limit = null;
        if ($this->pagination->isEnabled($operation, $context)) {
            $offset = $this->pagination->getPage($context);
            $limit = $this->pagination->getLimit($operation, $context);
        }
        $type = BonusTypeEnum::from($type);

        /** @var BonusRepositoryInterface $models */
        $models = $this->queryBus->ask(new GetBonusCollectionQuery(
            $type,
            $isJoker,
            $offset,
            $limit,
        ));

        $resources = [];
        /** @var BonusInterface $model */
        foreach ($models as $model) {
            $resources[] = BonusResource::fromModel($model);
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
