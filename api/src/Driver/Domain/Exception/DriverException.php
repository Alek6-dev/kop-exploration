<?php

declare(strict_types=1);

namespace App\Driver\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class DriverException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $uuid): self
    {
        throw new self(sprintf('Driver with id %s not found.', $uuid));
    }
}
