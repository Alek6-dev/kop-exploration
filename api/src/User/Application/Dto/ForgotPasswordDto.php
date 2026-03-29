<?php

declare(strict_types=1);

namespace App\User\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ForgotPasswordDto
{
    #[Assert\Email]
    public string $email;
}
