<?php

declare(strict_types=1);

namespace App\Race\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class RaceException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $uuid): self
    {
        throw new self(sprintf('Race with id %s not found.', $uuid));
    }
}
