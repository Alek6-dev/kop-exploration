<?php

declare(strict_types=1);

namespace App\User\Application\Dto;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

class CreateUserDto
{
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\PasswordStrength(
        minScore: Assert\PasswordStrength::STRENGTH_WEAK,
    )]
    public string $password;

    #[Assert\NotBlank]
    public string $pseudo;

    #[Vich\UploadableField(mapping: 'default', fileNameProperty: 'image')]
    public ?File $imageFile = null;
}
