<?php

declare(strict_types=1);

namespace App\Championship\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class ChampionshipRaceException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $uuid): self
    {
        throw new self(sprintf('Championship with id %s not found.', $uuid));
    }

    public static function raceNotAttachedToChampionship(string $raceUuid, string $championshipUuid): self
    {
        throw new self(sprintf('Race with id %s is not attached to the championship with id %s.', $raceUuid, $championshipUuid));
    }

    public static function transitionStatusNotPossible(string $fromStatus, string $toStatus): self
    {
        throw new self(sprintf('Impossible to set status: %s from status: %s', $toStatus, $fromStatus));
    }
}
