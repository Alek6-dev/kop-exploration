<?php

declare(strict_types=1);

namespace App\Admin\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class ForgotPasswordActionDto
{
    #[Assert\NotBlank]
    #[Assert\PasswordStrength(
        minScore: Assert\PasswordStrength::STRENGTH_WEAK,
        message: 'form.field.plain_password.error.password_strength.message',
    )]
    public ?string $password = null;
}
