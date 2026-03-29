<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class UserVisitorException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $uuid): self
    {
        throw new self(sprintf('User with id %s not found.', $uuid));
    }

    public static function emailNotFound(string $email): self
    {
        throw new self(sprintf('User with email %s not found.', $email));
    }

    public static function emailAlreadyUsed(string $email): self
    {
        throw new self(sprintf('User with email %s already exists.', $email));
    }

    public static function pseudoAlreadyUsed(string $pseudo): self
    {
        throw new self(sprintf('User with pseudo %s already exists.', $pseudo));
    }

    public static function validationExpired(string $emailValidationToken): self
    {
        throw new self(sprintf('Token validation %s expired.', $emailValidationToken));
    }

    public static function resetPasswordExpired(string $resetPasswordToken): self
    {
        throw new self(sprintf('Reset password token %s expired.', $resetPasswordToken));
    }

    public static function notPlayer(string $playerUuid): self
    {
        throw new self(sprintf('Player with id %s is not attached to the user.', $playerUuid));
    }

    public static function notAllowedToUpdateInformation(): self
    {
        throw new self('You are not allowed to update this user.');
    }

    public static function notAllowedToDeleteUser(): self
    {
        throw new self('You are not allowed to delete this user.');
    }

    public static function confirmationNotPossible(string $uuid): self
    {
        throw new self(sprintf('User with id %s cannot be confirmed.', $uuid));
    }

    public static function emailValidationTokenNotFound(string $uuid): self
    {
        throw new self(sprintf('User with id %s has not validation token.', $uuid));
    }

    public static function resetPasswordTokenNotFound(string $uuid): self
    {
        throw new self(sprintf('User with id %s has not reset password token.', $uuid));
    }
}
