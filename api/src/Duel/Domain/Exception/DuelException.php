<?php

declare(strict_types=1);

namespace App\Duel\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class DuelException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function driverNotFound(string $uuid): self
    {
        throw new self(sprintf('Driver with id %s not found.', $uuid));
    }

    public static function notFound(string $uuid): self
    {
        throw new self(sprintf('Duel with id %s not found.', $uuid));
    }

    public static function noMatch(string $playerUuid, string $raceUuid, string $seasonUuid): self
    {
        throw new self(sprintf('Player with id %s has no Duel on race id %s (season: %s).', $playerUuid, $raceUuid, $seasonUuid));
    }

    public static function noResult(string $raceUuid, string $championshipUuid): self
    {
        throw new self(sprintf('No duels found for race id %s (Championship: %s).', $raceUuid, $championshipUuid));
    }

    public static function notEnoughUsageForDriver(string $playerUuid, string $driverUuid): self
    {
        throw new self(sprintf('Player with id %s has not enough usage for driver with id %s.', $playerUuid, $driverUuid));
    }

    public static function raceIsNotActive(string $uuid, string $raceUuid): self
    {
        throw new self(sprintf('Race with id %s is not active on duel %s.', $raceUuid, $uuid));
    }
}
