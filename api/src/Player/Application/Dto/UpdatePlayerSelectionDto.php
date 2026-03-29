<?php

declare(strict_types=1);

namespace App\Player\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePlayerSelectionDto
{
    public function __construct(
        #[Assert\NotBlank(allowNull: true)]
        public ?string $selectedTeamUuid,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $selectedDriver1Uuid,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $selectedDriver2Uuid,
    ) {
    }
}
