<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

trait InvalidDataExceptionTrait
{
    public static function invalidData(string $data): self
    {
        throw new self(sprintf('Invalid data: %s', $data));
    }
}
