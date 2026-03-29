<?php

declare(strict_types=1);

namespace App\Strategy\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class StrategyException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function driverNotFound(string $uuid): self
    {
        throw new self(sprintf('Driver with id %s not found.', $uuid));
    }

    public static function notFound(string $uuid): self
    {
        throw new self(sprintf('Strategy with id %s not found.', $uuid));
    }

    public static function noMatch(string $playerUuid, string $raceUuid, string $championshipUuid): self
    {
        throw new self(sprintf('Player with id %s has no strategy on race id %s (Championship: %s).', $playerUuid, $raceUuid, $championshipUuid));
    }

    public static function noResult(string $raceUuid, string $championshipUuid): self
    {
        throw new self(sprintf('No strategies found for race id %s (Championship: %s).', $raceUuid, $championshipUuid));
    }

    public static function notEnoughUsageForDriver(string $playerUuid, string $driverUuid): self
    {
        throw new self(sprintf('Player with id %s has not enough usage for driver with id %s.', $playerUuid, $driverUuid));
    }

    public static function raceIsNotActive(string $uuid): self
    {
        throw new self(sprintf('Strategy with id %s is not active', $uuid));
    }
}
