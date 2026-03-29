<?php

declare(strict_types=1);

namespace App\Performance\Infrastructure\Doctrine\Entity\Trait;

use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\Performance\Domain\Enum\QualificationPositionPointEnum;
use App\Performance\Domain\Enum\RacePositionPointEnum;
use App\Performance\Domain\Enum\SprintPositionPointEnum;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

trait DriverPerformanceTrait
{
    use HasBonusTrait;
    use IdableTrait;
    use PerformanceTrait;
    use TimestampableTrait;
    use UuidableTrait;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    #[ORM\ManyToOne(targetEntity: Driver::class, inversedBy: 'driverPerformances')]
    private ?DriverInterface $driver = null;

    #[Assert\Type(type: QualificationPositionPointEnum::class)]
    #[ORM\Column(type: Types::INTEGER, length: 2, enumType: QualificationPositionPointEnum::class)]
    private ?QualificationPositionPointEnum $qualificationPoints = null;

    #[Assert\Type(type: RacePositionPointEnum::class)]
    #[ORM\Column(type: Types::INTEGER, length: 2, enumType: RacePositionPointEnum::class)]
    private ?RacePositionPointEnum $racePoints = null;

    #[Assert\Type(type: SprintPositionPointEnum::class)]
    #[ORM\Column(type: Types::INTEGER, length: 2, nullable: true, enumType: SprintPositionPointEnum::class)]
    private ?SprintPositionPointEnum $sprintPoints = null;

    #[ORM\Column(type: Types::STRING, length: 2, nullable: true)]
    private ?string $sprintPosition = null;

    #[ORM\Column(type: Types::STRING, length: 2)]
    private ?string $qualificationPosition = null;

    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, length: 3)]
    private ?int $positionGain = 0;

    public function getDriver(): ?DriverInterface
    {
        return $this->driver;
    }

    public function setDriver(DriverInterface $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getQualificationPoints(): ?QualificationPositionPointEnum
    {
        return $this->qualificationPoints;
    }

    public function setQualificationPoints(QualificationPositionPointEnum $qualificationPoints): static
    {
        $this->qualificationPoints = $qualificationPoints;

        return $this;
    }

    public function getRacePoints(): ?RacePositionPointEnum
    {
        return $this->racePoints;
    }

    public function setRacePoints(RacePositionPointEnum $racePoints): static
    {
        $this->racePoints = $racePoints;

        return $this;
    }

    public function getSprintPoints(): ?SprintPositionPointEnum
    {
        return $this->sprintPoints;
    }

    public function setSprintPoints(?SprintPositionPointEnum $sprintPoints): static
    {
        $this->sprintPoints = $sprintPoints;

        return $this;
    }

    public function getPositionGain(): ?int
    {
        return $this->positionGain;
    }

    public function setPositionGain(int $positionGain): static
    {
        $this->positionGain = $positionGain;

        return $this;
    }

    public function getSprintPosition(): ?string
    {
        return $this->sprintPosition;
    }

    public function setSprintPosition(?string $position): static
    {
        $this->sprintPosition = $position;

        return $this;
    }

    public function getQualificationPosition(): ?string
    {
        return $this->qualificationPosition;
    }

    public function setQualificationPosition(string $position): static
    {
        $this->qualificationPosition = $position;

        return $this;
    }
}
