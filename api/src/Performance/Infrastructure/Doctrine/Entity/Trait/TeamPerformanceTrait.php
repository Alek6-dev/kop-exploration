<?php

declare(strict_types=1);

namespace App\Performance\Infrastructure\Doctrine\Entity\Trait;

use App\Performance\Domain\Enum\TeamMultiplierEnum;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

trait TeamPerformanceTrait
{
    use IdableTrait;
    use PerformanceTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'performances')]
    private ?TeamInterface $team = null;

    #[Assert\Positive]
    #[ORM\Column(type: Types::INTEGER, length: 2)]
    private ?int $multiplier = TeamMultiplierEnum::P_DEFAULT->value;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getTeam(): ?TeamInterface
    {
        return $this->team;
    }

    public function setTeam(TeamInterface $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getMultiplier(): int
    {
        return $this->multiplier;
    }

    public function setMultiplier(int $multiplier): static
    {
        $this->multiplier = $multiplier;

        return $this;
    }
}
