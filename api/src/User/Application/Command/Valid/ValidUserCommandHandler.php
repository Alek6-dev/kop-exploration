<?php

declare(strict_types=1);

namespace App\User\Application\Command\Valid;

use App\Shared\Application\Command\AsCommandHandler;
use App\Shared\Domain\Enum\User\StatusEnum;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;

#[AsCommandHandler]
final readonly class ValidUserCommandHandler
{
    public const int TOKEN_VALIDITY_PERIOD = 3;

    public function __construct(
        private UserVisitorRepositoryInterface $repository,
    ) {
    }

    public function __invoke(ValidUserCommand $command): UserVisitorInterface
    {
        /** @var ?UserVisitorInterface $model */
        $model = $this->repository->getByEmailValidationToken($command->token);
        if (!$model || StatusEnum::EMAIL_TO_VALIDATE !== $model->getStatus()) {
            throw UserVisitorException::validationExpired($command->token);
        }

        $model->validate();

        $this->repository->update($model);

        return $model;
    }
}
