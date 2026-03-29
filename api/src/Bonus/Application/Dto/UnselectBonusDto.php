<?php

declare(strict_types=1);

namespace App\Bonus\Application\Dto;

use App\Bonus\Domain\Enum\BonusTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class UnselectBonusDto
{
    public function __construct(
        #[Assert\Uuid]
        public ?string $entityUuid,
        #[Assert\Type(BonusTypeEnum::class)]
        public ?BonusTypeEnum $type,
    ) {
    }
}
