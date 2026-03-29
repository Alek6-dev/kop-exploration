<?php

declare(strict_types=1);

namespace App\Player\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class PlayerException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $uuid): self
    {
        throw new self(sprintf('Player with id %s not found.', $uuid));
    }

    public static function maxPlayerReached(string $championshipName): static
    {
        throw new self(sprintf('The championship "%s" is full.', $championshipName));
    }

    public static function alreadyRegistered(string $userName, string $championshipName): static
    {
        throw new self(sprintf('Player "%s" already registered on championship %s.', $userName, $championshipName));
    }

    public static function nameAlreadyUsed(string $playerName, string $championshipName): static
    {
        throw new self(sprintf('Player name "%s" already used on championship %s.', $playerName, $championshipName));
    }

    public static function amountToSubtractIsTooHigh(string $playerUuid, int $total, int $remainingBudget): self
    {
        throw new self(sprintf('Total amount (%s) too high. Remaining budget %s for player with id %s.', $total, $remainingBudget, $playerUuid));
    }
}
