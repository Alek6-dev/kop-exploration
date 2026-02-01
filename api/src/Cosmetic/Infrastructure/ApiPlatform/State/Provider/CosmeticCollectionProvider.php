<?php

declare(strict_types=1);

namespace App\Cosmetic\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Cosmetic\Application\Query\Collection\GetCosmeticsQuery;
use App\Cosmetic\Domain\Enum\TypeCosmeticEnum;
use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Cosmetic\Domain\Repository\CosmeticRepositoryInterface;
use App\Cosmetic\Infrastructure\ApiPlatform\Resource\CosmeticResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\ApiPlatform\State\Paginator;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<Paginator|array<CosmeticResource>>
 */
final readonly class CosmeticCollectionProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Pagination $pagination,
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Paginator|array
    {
        $name = $context['filters']['name'] ?? null;
        $type = $context['filters']['type'] ?? null;
        $offset = $limit = null;

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();

        if ($this->pagination->isEnabled($operation, $context)) {
            $offset = $this->pagination->getPage($context);
            $limit = $this->pagination->getLimit($operation, $context);
        }
        if ($type) {
            $type = TypeCosmeticEnum::from((int) $type);
        }

        /** @var CosmeticRepositoryInterface $models */
        $models = $this->queryBus->ask(new GetCosmeticsQuery($name, $type, $offset, $limit));

        $resources = [];
        /** @var CosmeticInterface $model */
        foreach ($models as $model) {
            $resources[] = CosmeticResource::fromModel($model, $user);
        }

        if (null !== $paginator = $models->paginator()) {
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
