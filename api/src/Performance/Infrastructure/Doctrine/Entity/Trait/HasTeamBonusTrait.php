<?php

declare(strict_types=1);

namespace App\Performance\Infrastructure\Doctrine\Entity\Trait;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait HasTeamBonusTrait
{
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, length: 6, nullable: true)]
    private ?int $multiplierWithBonus = null;

    public function getMultiplierWithBonus(): ?int
    {
        return $this->multiplierWithBonus;
    }

    public function setMultiplierWithBonus(?int $points): static
    {
        $this->multiplierWithBonus = $points;

        return $this;
    }
}
