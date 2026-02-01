<?php

declare(strict_types=1);

namespace App\User\Application\Command\Update;

use App\Shared\Application\Command\AsCommandHandler;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommandHandler]
final readonly class UpdateUserVisitorCommandHandler
{
    public function __construct(
        private UserVisitorRepositoryInterface $repository,
        private UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function __invoke(UpdateUserVisitorCommand $command): UserVisitorInterface
    {
        /** @var ?UserVisitorInterface $model */
        $model = $this->repository->getByUuid($command->uuid);

        if (!$model) {
            throw UserVisitorException::notFound($command->uuid);
        }

        if ($command->email) {
            $model->setEmail($command->email);
        }

        if ($command->pseudo) {
            $model->setPseudo($command->pseudo);
        }

        if ($command->password) {
            $model->setPassword($this->userPasswordHasher->hashPassword($model, $command->password));
        }

        if ($command->image) {
            $model->setImage($command->image);
        }

        return $model;
    }
}
