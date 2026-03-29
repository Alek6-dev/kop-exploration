<?php

declare(strict_types=1);

namespace App\User\Application\Query\Get;

use App\Shared\Application\Query\AsQueryHandler;
use App\Shared\Domain\Enum\User\StatusEnum;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;

#[AsQueryHandler]
final readonly class GetUserVisitorByResetPasswordTokenQueryHandler
{
    public function __construct(private UserVisitorRepositoryInterface $repository)
    {
    }

    public function __invoke(GetUserVisitorByResetPasswordTokenQuery $query): UserVisitorInterface
    {
        /** @var ?UserVisitorInterface $model */
        $model = $this->repository->getByResetPasswordToken($query->token);

        if (!$model || StatusEnum::PASSWORD_TO_CREATE !== $model->getStatus()) {
            throw UserVisitorException::resetPasswordExpired($query->token);
        }

        return $model;
    }
}
