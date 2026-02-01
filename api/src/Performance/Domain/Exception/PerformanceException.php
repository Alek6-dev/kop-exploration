<?php

declare(strict_types=1);

namespace App\Performance\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class PerformanceException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $type, string $seasonName, string $raceName, string $entityName): self
    {
        throw new self(sprintf('%s %s has no performance on race %s on season %s.', $type, $entityName, $raceName, $seasonName));
    }
}
