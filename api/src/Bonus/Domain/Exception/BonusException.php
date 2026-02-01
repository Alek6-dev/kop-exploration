<?php

declare(strict_types=1);

namespace App\Bonus\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class BonusException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $uuid): self
    {
        throw new self(sprintf('Bonus with id %s not found.', $uuid));
    }

    public static function wrongType(string $type): self
    {
        throw new self(sprintf('Bonus type %s not found.', $type));
    }

    public static function impossibleToSelect(): self
    {
        throw new self('Impossible to select a bonus');
    }

    public static function impossibleToApply(): self
    {
        throw new self('Impossible to apply bonus');
    }

    public static function impossibleToApplyBonusToPerformance(string $bonusUuid, string $performanceUuid): self
    {
        throw new self(sprintf('Impossible to apply a bonus with id %s to the performance with id %s.', $bonusUuid, $performanceUuid));
    }

    public static function impossibleToApplyBonusDueToWrongDriverSelection(string $bonusUuid, string $performanceUuid): self
    {
        throw new self(sprintf('Impossible to apply a bonus with id %s to the performance with id %s. Wrong driver.', $bonusUuid, $performanceUuid));
    }

    public static function impossibleToApplyBonusDueToWrongTeamSelection(string $bonusUuid, string $performanceUuid): self
    {
        throw new self(sprintf('Impossible to apply a bonus with id %s to the performance with id %s. Wrong team.', $bonusUuid, $performanceUuid));
    }

    public static function impossibleToUnselect(): self
    {
        throw new self('Impossible to unselect a bonus');
    }

    public static function bonusAlreadySelected(string $playerUuid): self
    {
        throw new self(sprintf('A bonus is already selected by player id %s', $playerUuid));
    }

    public static function needATarget(string $uuid): self
    {
        throw new self(sprintf('Bonus with id %s needs a target player', $uuid));
    }
}
