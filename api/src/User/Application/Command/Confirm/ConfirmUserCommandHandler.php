<?php

declare(strict_types=1);

namespace App\User\Application\Command\Confirm;

use App\Shared\Application\Command\AsCommandHandler;
use App\Shared\Domain\Enum\User\StatusEnum;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;

#[AsCommandHandler]
final readonly class ConfirmUserCommandHandler
{
    public function __construct(
        private UserVisitorRepositoryInterface $repository,
    ) {
    }

    public function __invoke(ConfirmUserCommand $command): UserVisitorInterface
    {
        /** @var ?UserVisitorInterface $model */
        $model = $this->repository->getByUuid($command->uuid);
        if (!$model || StatusEnum::WAITING_ADMIN_CONFIRMATION !== $model->getStatus()) {
            throw UserVisitorException::confirmationNotPossible($command->uuid);
        }

        $model->confirm();

        $this->repository->update($model);

        return $model;
    }
}
