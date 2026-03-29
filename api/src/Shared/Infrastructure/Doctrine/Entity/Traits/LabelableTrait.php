<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

trait LabelableTrait
{
    #[ORM\Column(type: Types::STRING, length: 191, nullable: true)]
    protected ?string $label = null;

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    #[Pure]
    public function __toString(): string
    {
        return (string) $this->getLabel();
    }
}
