<?php

declare(strict_types=1);

namespace App\Bid\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class BidException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $uuid): self
    {
        throw new self(sprintf('Betting Round with id %s not found.', $uuid));
    }

    public static function bidAmountMustBeGreaterThanMinValue(int $total, int $minValue, string $uuid): self
    {
        throw new self(sprintf('Bid amount (%s) must be greater than min value (%s) for the id %s.', $total, $minValue, $uuid));
    }

    public static function playerHasAlreadySelectedTeam(string $playerUuid): self
    {
        throw new self(sprintf('Player with id %s has already selected a team.', $playerUuid));
    }

    public static function needToSelectATeam(string $playerUuid): self
    {
        throw new self(sprintf('Player with id %s must select a team.', $playerUuid));
    }

    public static function needToSelectOneMoreDriver(string $playerUuid): self
    {
        throw new self(sprintf('Player with id %s must select one more driver.', $playerUuid));
    }

    public static function playerHasAlreadySelectedDrivers(string $playerUuid): self
    {
        throw new self(sprintf('Player with id %s has already selected 2 drivers.', $playerUuid));
    }

    public static function driverAlreadySelectedByAnotherPlayer(string $driverUuid): self
    {
        throw new self(sprintf('Driver with id %s is already selected by another player.', $driverUuid));
    }

    public static function teamAlreadySelectedByAnotherPlayer(string $teamUuid): self
    {
        throw new self(sprintf('Team with id %s is already selected by another player.', $teamUuid));
    }

    public static function bidAlreadySaved(string $playerUuid, int $round): self
    {
        throw new self(sprintf('Player with id %s has already registered for the bid no %s', $playerUuid, $round));
    }

    public static function newBettingRoundNotPossible(string $championshipUuid): self
    {
        throw new self(sprintf('Impossible to start a new betting round for championship with id %s', $championshipUuid));
    }
}
