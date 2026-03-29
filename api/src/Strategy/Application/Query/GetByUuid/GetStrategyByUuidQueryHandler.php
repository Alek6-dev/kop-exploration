<?php

declare(strict_types=1);

namespace App\Strategy\Application\Query\GetByUuid;

use App\Shared\Application\Query\AsQueryHandler;
use App\Strategy\Domain\Exception\StrategyException;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Domain\Repository\StrategyRepositoryInterface;

#[AsQueryHandler]
final readonly class GetStrategyByUuidQueryHandler
{
    public function __construct(private StrategyRepositoryInterface $repository)
    {
    }

    public function __invoke(GetStrategyByUuidQuery $query): StrategyInterface
    {
        /** @var ?StrategyInterface $strategy */
        $strategy = $this->repository
            ->getByUuid($query->uuid)
        ;

        if (!$strategy) {
            throw StrategyException::notFound($query->uuid);
        }

        return $strategy;
    }
}
