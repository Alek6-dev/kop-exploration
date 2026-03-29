<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\Shared\Domain\Enum\User\StatusEnum;
use App\Shared\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

interface UserVisitorRepositoryInterface extends UserRepositoryInterface, PasswordUpgraderInterface
{
    public function withEmail(string $email): static;

    public function getByEmail(string $email): ?UserVisitorInterface;

    public function withEmailValidationToken(string $emailValidationToken): static;

    public function getByEmailValidationToken(string $emailValidationToken): ?UserVisitorInterface;

    public function withResetPasswordToken(string $resetPasswordToken): static;

    public function getByResetPasswordToken(string $resetPasswordToken): ?UserVisitorInterface;

    /**
     * @param array<StatusEnum> $statuses
     */
    public function withStatuses(array $statuses): static;
}
