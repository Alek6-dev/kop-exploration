<?php

declare(strict_types=1);

namespace App\Championship\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class ChampionshipException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $uuid): self
    {
        throw new self(sprintf('Championship with id %s not found.', $uuid));
    }

    public static function invitationCodeNotFound(string $invitationCode): self
    {
        throw new self(sprintf('Championship with invitation code %s not found.', $invitationCode));
    }

    public static function wrongCreatorToStart(): self
    {
        throw new self('Only the creator can start the championship.');
    }

    public static function wrongCreatorToCancel(): self
    {
        throw new self('Only the creator can cancel the championship.');
    }

    public static function playerCountNotEven(): self
    {
        throw new self('The number of players must be even.');
    }

    public static function minPlayersNotReached(int $minPlayer): self
    {
        throw new self(sprintf('Championship must have %s players at least.', $minPlayer));
    }

    public static function cantBeStarted(string $uuid): self
    {
        throw new self(sprintf('Championship with id %s cannot be started.', $uuid));
    }

    public static function notReady(string $uuid): self
    {
        throw new self(sprintf('Championship with id %s is not ready.', $uuid));
    }

    public static function cantBeCancelled(string $uuid): self
    {
        throw new self(sprintf('Championship with id %s cannot be cancelled.', $uuid));
    }

    public static function notAPlayer(string $userUuid): self
    {
        throw new self(sprintf('User with id %s is not a player registered on this championship', $userUuid));
    }

    public static function wrongCreator(string $userUuid): self
    {
        throw new self(sprintf('User with id %s is not the creator', $userUuid));
    }

    public static function noActiveRace(string $uuid): self
    {
        throw new self(sprintf('Championship with id %s has no active race', $uuid));
    }
}
