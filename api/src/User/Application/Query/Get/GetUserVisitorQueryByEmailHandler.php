<?php

declare(strict_types=1);

namespace App\User\Application\Query\Get;

use App\Shared\Application\Query\AsQueryHandler;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;

#[AsQueryHandler]
final readonly class GetUserVisitorQueryByEmailHandler
{
    public function __construct(private UserVisitorRepositoryInterface $repository)
    {
    }

    public function __invoke(GetUserVisitorByEmailQuery $query): UserVisitorInterface
    {
        /** @var ?UserVisitorInterface $model */
        $model = $this->repository->getByEmail($query->email);
        if (!$model) {
            throw UserVisitorException::emailNotFound($query->email);
        }

        return $model;
    }
}
