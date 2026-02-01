<?php

declare(strict_types=1);

namespace App\Season\Domain\Exception;

class SeasonException extends \Exception
{
    public static function notActiveSeason(): self
    {
        return new self('No active season');
    }
}
