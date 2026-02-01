<?php

declare(strict_types=1);

namespace App\User\Application\Dto;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

class UpdateUserDto
{
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\NotBlank(allowNull: true)]
    #[Assert\PasswordStrength(
        minScore: Assert\PasswordStrength::STRENGTH_WEAK,
    )]
    public ?string $password = null;

    #[Assert\NotBlank(allowNull: true)]
    public ?string $pseudo = null;

    #[Vich\UploadableField(mapping: 'default', fileNameProperty: 'image')]
    public ?File $imageFile = null;
}
