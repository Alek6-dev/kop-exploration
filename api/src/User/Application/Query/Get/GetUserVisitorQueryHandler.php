<?php

declare(strict_types=1);

namespace App\User\Application\Query\Get;

use App\Shared\Application\Query\AsQueryHandler;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;

#[AsQueryHandler]
final readonly class GetUserVisitorQueryHandler
{
    public function __construct(private UserVisitorRepositoryInterface $repository)
    {
    }

    public function __invoke(GetUserVisitorQuery $query): UserVisitorInterface
    {
        /** @var ?UserVisitorInterface $model */
        $model = $this->repository->getByUuid($query->uuid);

        if (!$model) {
            throw UserVisitorException::notFound($query->uuid);
        }

        return $model;
    }
}
