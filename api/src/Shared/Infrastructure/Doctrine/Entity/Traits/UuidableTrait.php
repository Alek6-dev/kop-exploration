<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Entity\Traits;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;

trait UuidableTrait
{
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ApiProperty(readable: true, writable: false, identifier: true)]
    private string $uuid;

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
