<?php

declare(strict_types=1);

namespace App\SeasonGame\Domain\Exception;

final class SeasonGameException extends \RuntimeException
{
    public static function noActiveSeason(): self
    {
        return new self('No active F1 season found.', 404);
    }

    public static function participationAlreadyExists(): self
    {
        return new self('Player already enrolled in this season.', 409);
    }

    public static function rosterAlreadyExists(): self
    {
        return new self('Player already has a roster for this season.', 409);
    }

    public static function rosterNotFound(): self
    {
        return new self('Season roster not found for this player.', 404);
    }

    public static function insufficientBudget(): self
    {
        return new self('Insufficient season wallet balance.', 422);
    }

    public static function noUsagesLeft(string $element): self
    {
        return new self(sprintf('No usages left for element "%s".', $element), 422);
    }

    public static function strategyDeadlinePassed(): self
    {
        return new self('Strategy deadline has passed for this race.', 422);
    }

    public static function strategyAlreadyLocked(): self
    {
        return new self('Strategy is locked and cannot be modified.', 422);
    }

    public static function invalidRosterSize(): self
    {
        return new self('Roster must contain exactly 4 drivers and 2 teams.', 422);
    }

    public static function budgetExceeded(): self
    {
        return new self('Team composition exceeds the 500M budget.', 422);
    }
}
