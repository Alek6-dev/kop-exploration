<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Entity\Traits;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait IdableTrait
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER, unique: true)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ApiProperty(readable: false, writable: false, identifier: false)]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
