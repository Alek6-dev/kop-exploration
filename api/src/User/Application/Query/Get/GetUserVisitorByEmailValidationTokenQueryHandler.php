<?php

declare(strict_types=1);

namespace App\User\Application\Query\Get;

use App\Shared\Application\Query\AsQueryHandler;
use App\Shared\Domain\Enum\User\StatusEnum;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;

#[AsQueryHandler]
final readonly class GetUserVisitorByEmailValidationTokenQueryHandler
{
    public function __construct(private UserVisitorRepositoryInterface $repository)
    {
    }

    public function __invoke(GetUserVisitorByEmailValidationTokenQuery $query): UserVisitorInterface
    {
        /** @var ?UserVisitorInterface $model */
        $model = $this->repository->getByEmailValidationToken($query->token);

        if (!$model || StatusEnum::EMAIL_TO_VALIDATE !== $model->getStatus()) {
            throw UserVisitorException::validationExpired($query->token);
        }

        return $model;
    }
}
