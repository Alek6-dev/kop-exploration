<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Command\ApplySeasonBonus;

use App\SeasonGame\Domain\Enum\SeasonBonusTypeEnum;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonParticipation;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class ApplySeasonBonusCommand implements CommandInterface
{
    public function __construct(
        public SeasonParticipation $participation,
        public string $raceUuid,
        public SeasonBonusTypeEnum $bonusType,
    ) {
    }
}
