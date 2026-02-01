<?php

declare(strict_types=1);

namespace App\Performance\Infrastructure\Doctrine\Entity\Trait;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait PerformanceTrait
{
    #[Assert\NotNull]
    #[Assert\Length(max: 3)]
    #[ORM\Column(type: Types::INTEGER, length: 3, nullable: true)]
    private ?int $position = null;

    #[Assert\NotNull]
    #[Assert\Length(max: 3)]
    #[ORM\Column(type: Types::INTEGER, length: 3, nullable: true)]
    private ?int $points = null;

    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, length: 6, nullable: true)]
    private ?int $score = 0;

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $position): static
    {
        $this->points = $position;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): static
    {
        $this->score = $score;

        return $this;
    }
}
