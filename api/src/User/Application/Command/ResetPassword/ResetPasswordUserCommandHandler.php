<?php

declare(strict_types=1);

namespace App\User\Application\Command\ResetPassword;

use App\Shared\Application\Command\AsCommandHandler;
use App\Shared\Domain\Enum\User\StatusEnum;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use function Symfony\Component\Clock\now;

#[AsCommandHandler]
final readonly class ResetPasswordUserCommandHandler
{
    public function __construct(
        private UserVisitorRepositoryInterface $repository,
        private UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function __invoke(ResetPasswordUserCommand $command): UserVisitorInterface
    {
        /** @var ?UserVisitorInterface $model */
        $model = $this->repository->getByResetPasswordToken($command->token);
        if (!$model || StatusEnum::PASSWORD_TO_CREATE !== $model->getStatus() || $model->getResetPasswordRequestedAt() < now()) {
            throw UserVisitorException::resetPasswordExpired($command->token);
        }

        $model->setResetPasswordToken(null)
            ->setPassword($this->userPasswordHasher->hashPassword($model, $command->password))
            ->setStatus(StatusEnum::CREATED)
            ->setResetPasswordRequestedAt(null)
        ;

        return $model;
    }
}
