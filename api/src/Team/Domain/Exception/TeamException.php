<?php

declare(strict_types=1);

namespace App\Team\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class TeamException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $uuid): self
    {
        throw new self(sprintf('Team with id %s not found.', $uuid));
    }
}
