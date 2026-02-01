<?php

declare(strict_types=1);

namespace App\User\Application\ForgotPassword;

use App\Shared\Application\Command\AsCommandHandler;
use App\Shared\Domain\Enum\User\StatusEnum;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;
use Symfony\Component\Uid\UuidV4;

#[AsCommandHandler]
final readonly class ForgotPasswordCommandHandler
{
    public const int TOKEN_VALIDITY_PERIOD = 3;

    public function __construct(
        private UserVisitorRepositoryInterface $repository,
    ) {
    }

    public function __invoke(ForgotPasswordCommand $command): UserVisitorInterface
    {
        /** @var ?UserVisitorInterface $model */
        $model = $this->repository->getByEmail($command->email);
        if (!$model) {
            throw UserVisitorException::emailNotFound($command->email);
        }

        $model
            ->setResetPasswordToken((string) (new UuidV4()))
            ->setResetPasswordRequestedAt((new \DateTimeImmutable())->add(new \DateInterval(sprintf('PT%sH', self::TOKEN_VALIDITY_PERIOD))))
            ->setStatus(StatusEnum::PASSWORD_TO_CREATE)
        ;

        return $model;
    }
}
