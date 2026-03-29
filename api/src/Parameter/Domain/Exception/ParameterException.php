<?php

declare(strict_types=1);

namespace App\Parameter\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class ParameterException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $code): self
    {
        throw new self(sprintf('Parameter with code %s not found.', $code));
    }
}
