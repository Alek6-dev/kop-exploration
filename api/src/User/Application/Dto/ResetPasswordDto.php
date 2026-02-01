<?php

declare(strict_types=1);

namespace App\User\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordDto
{
    #[Assert\NotBlank]
    #[Assert\PasswordStrength(
        minScore: Assert\PasswordStrength::STRENGTH_WEAK,
    )]
    public string $password;
}
