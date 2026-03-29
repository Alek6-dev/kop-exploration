<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Championship\Application\Query\Collection\GetChampionshipsQuery;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Championship\Infrastructure\ApiPlatform\Resource\ChampionshipResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\ApiPlatform\State\Paginator;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<Paginator|array<ChampionshipResource>>
 */
final readonly class ChampionshipCollectionProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Pagination $pagination,
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Paginator|array
    {
        $isActive = (bool) $context['filters']['isActive'];
        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();
        $offset = $limit = null;

        if ($this->pagination->isEnabled($operation, $context)) {
            $offset = $this->pagination->getPage($context);
            $limit = $this->pagination->getLimit($operation, $context);
        }

        /** @var ChampionshipRepositoryInterface $models */
        $models = $this->queryBus->ask(new GetChampionshipsQuery(
            $isActive,
            $user,
            $offset,
            $limit,
        ));

        $resources = [];
        /** @var ChampionshipInterface $model */
        foreach ($models as $model) {
            $resources[] = ChampionshipResource::fromModel($model);
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
