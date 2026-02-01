<?php

declare(strict_types=1);

namespace App\Cosmetic\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class CosmeticException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $uuid): self
    {
        throw new self(sprintf('Championship with id %s not found.', $uuid));
    }

    public static function alreadyPossessed(string $userUuid, string $cosmeticUuid): self
    {
        throw new self(sprintf('User with id %s already owns the cosmetic with id %s.', $userUuid, $cosmeticUuid));
    }

    public static function notPossessed(string $userUuid, string $cosmeticUuid): self
    {
        throw new self(sprintf('User with id %s does not own the cosmetic with id %s.', $userUuid, $cosmeticUuid));
    }
}
