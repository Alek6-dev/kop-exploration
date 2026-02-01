<?php

declare(strict_types=1);

namespace App\User\Application\Dto;

class TokenDto
{
    public function __construct(
        public string $token,
    ) {
    }
}
