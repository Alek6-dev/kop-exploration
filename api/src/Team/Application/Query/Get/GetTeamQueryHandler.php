<?php

declare(strict_types=1);

namespace App\Team\Application\Query\Get;

use App\Shared\Application\Query\AsQueryHandler;
use App\Team\Domain\Exception\TeamException;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Domain\Repository\TeamRepositoryInterface;

#[AsQueryHandler]
final readonly class GetTeamQueryHandler
{
    public function __construct(private TeamRepositoryInterface $repository)
    {
    }

    public function __invoke(GetTeamQuery $query): TeamInterface
    {
        /** @var ?TeamInterface $model */
        $model = $this->repository->getByUuid($query->uuid);

        if (!$model) {
            throw TeamException::notFound($query->uuid);
        }

        return $model;
    }
}
