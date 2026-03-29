<?php

declare(strict_types=1);

namespace App\Result\Infrastructure\Doctrine\Entity;

use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\Result\Domain\Enum\TypeResultEnum;
use App\Result\Domain\Model\ResultInterface;
use App\Result\Domain\Model\ResultLapInterface;
use App\Result\Infrastructure\Doctrine\Repository\DoctrineResultLapRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineResultLapRepository::class)]
class ResultLap implements ResultLapInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\NotNull]
    #[Assert\Positive]
    #[ORM\Column(type: Types::INTEGER, length: 3)]
    private ?int $noLap = 1;
    #[Assert\NotNull]
    #[Assert\Length(max: 3)]
    #[ORM\Column(type: Types::STRING, length: 3)]
    private ?string $place = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::INTEGER, length: 1, enumType: TypeResultEnum::class)]
    private ?TypeResultEnum $type;

    #[ORM\ManyToOne(targetEntity: Driver::class, inversedBy: 'results')]
    private ?DriverInterface $driver;

    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'results')]
    private ?TeamInterface $team;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Result::class, inversedBy: 'resultLaps')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?ResultInterface $result;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getResult(): ?ResultInterface
    {
        return $this->result;
    }

    public function setResult(?ResultInterface $result): static
    {
        $this->result = $result;

        return $this;
    }

    public function getNoLap(): ?int
    {
        return $this->noLap;
    }

    public function setNoLap(?int $noLap): static
    {
        $this->noLap = $noLap;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getType(): ?TypeResultEnum
    {
        return $this->type;
    }

    public function setType(?TypeResultEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDriver(): ?DriverInterface
    {
        return $this->driver;
    }

    public function setDriver(?DriverInterface $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getTeam(): ?TeamInterface
    {
        return $this->team;
    }

    public function setTeam(?TeamInterface $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function __toString(): string
    {
        return $this->noLap.' '.$this->place;
    }
}
