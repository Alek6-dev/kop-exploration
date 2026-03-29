<?php

declare(strict_types=1);

namespace App\Parameter\Application\Query\Collection;

use App\Parameter\Domain\Repository\ParameterRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetAllParametersQueryHandler
{
    public function __construct(private ParameterRepositoryInterface $repository)
    {
    }

    /**
     * @return ?ParameterRepositoryInterface[]
     */
    public function __invoke(GetAllParametersQuery $query): ?array
    {
        return $this->repository->getAll();
    }
}
