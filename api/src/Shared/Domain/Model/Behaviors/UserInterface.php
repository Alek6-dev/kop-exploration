<?php

declare(strict_types=1);

namespace App\Shared\Domain\Model\Behaviors;

use App\Shared\Domain\Enum\User\StatusEnum;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

interface UserInterface extends Stringifyable, Idable, Uuidable, SecurityUserInterface, PasswordAuthenticatedUserInterface, Timestampable
{
    public function getFirstName(): ?string;

    public function setFirstName(?string $firstName): static;

    public function getLastName(): ?string;

    public function setLastName(?string $lastName): static;

    public function getEmail(): string;

    public function setEmail(string $email): static;

    public function getMainRole(): ?string;

    public function setMainRole(string $role): static;

    /**
     * @param array<int, string> $roles
     */
    public function setRoles(array $roles): static;

    public function setPassword(?string $password): static;

    public function getResetPasswordToken(): ?string;

    public function setResetPasswordToken(?string $resetPasswordToken): static;

    public function getResetPasswordRequestedAt(): ?\DateTimeImmutable;

    public function setResetPasswordRequestedAt(?\DateTimeImmutable $resetPasswordRequestedAt): static;

    public function getStatus(): StatusEnum;

    public function setStatus(StatusEnum $status): static;

    public function __toString(): string;
}
