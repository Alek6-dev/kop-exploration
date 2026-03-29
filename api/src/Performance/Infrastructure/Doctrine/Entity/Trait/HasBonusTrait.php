<?php

declare(strict_types=1);

namespace App\Performance\Infrastructure\Doctrine\Entity\Trait;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait HasBonusTrait
{
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, length: 6, nullable: true)]
    private ?int $scoreWithBonus = 0;

    public function getScoreWithBonus(): ?int
    {
        return $this->scoreWithBonus;
    }

    public function setScoreWithBonus(?int $score): static
    {
        $this->scoreWithBonus = $score;

        return $this;
    }
}
