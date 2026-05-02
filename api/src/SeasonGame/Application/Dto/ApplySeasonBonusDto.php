<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Dto;

use App\SeasonGame\Domain\Enum\SeasonBonusTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class ApplySeasonBonusDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public ?string $raceUuid = null,

        #[Assert\NotNull]
        public ?SeasonBonusTypeEnum $bonusType = null,
    ) {
    }
}
