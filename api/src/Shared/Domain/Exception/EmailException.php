<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

class EmailException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function unknownError(): self
    {
        throw new self('Unknown error. Email not sent.');
    }
}
