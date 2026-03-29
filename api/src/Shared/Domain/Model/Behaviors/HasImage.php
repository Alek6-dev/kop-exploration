<?php

declare(strict_types=1);

namespace App\Shared\Domain\Model\Behaviors;

use Symfony\Component\HttpFoundation\File\File;

interface HasImage
{
    public function getImageFile(): ?File;

    public function setImageFile(?File $imageFile): static;

    public function getImage(): ?string;

    public function setImage(?string $image): static;

    public function getRelativeImagePath(): ?string;
}
