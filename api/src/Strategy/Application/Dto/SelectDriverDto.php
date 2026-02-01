<?php

declare(strict_types=1);

namespace App\Strategy\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class SelectDriverDto
{
    public function __construct(
        #[Assert\Uuid]
        public string $driverUuid,
    ) {
    }
}
