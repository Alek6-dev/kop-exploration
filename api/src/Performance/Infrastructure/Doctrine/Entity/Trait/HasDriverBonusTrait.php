<?php

declare(strict_types=1);

namespace App\Performance\Infrastructure\Doctrine\Entity\Trait;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait HasDriverBonusTrait
{
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, length: 6, nullable: true)]
    private ?int $qualificationPointsWithBonus = null;

    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, length: 6, nullable: true)]
    private ?int $racePointsWithBonus = null;

    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, length: 6, nullable: true)]
    private ?int $sprintPointsWithBonus = null;

    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, length: 6, nullable: true)]
    private ?int $positionGainWithBonus = null;

    public function getQualificationPointsWithBonus(): ?int
    {
        return $this->qualificationPointsWithBonus;
    }

    public function setQualificationPointsWithBonus(?int $points): static
    {
        $this->qualificationPointsWithBonus = $points;

        return $this;
    }

    public function getRacePointsWithBonus(): ?int
    {
        return $this->racePointsWithBonus;
    }

    public function setRacePointsWithBonus(?int $points): static
    {
        $this->racePointsWithBonus = $points;

        return $this;
    }

    public function getSprintPointsWithBonus(): ?int
    {
        return $this->sprintPointsWithBonus;
    }

    public function setSprintPointsWithBonus(?int $points): static
    {
        $this->sprintPointsWithBonus = $points;

        return $this;
    }

    public function getPositionGainWithBonus(): ?int
    {
        return $this->positionGainWithBonus;
    }

    public function setPositionGainWithBonus(?int $points): static
    {
        $this->positionGainWithBonus = $points;

        return $this;
    }
}
