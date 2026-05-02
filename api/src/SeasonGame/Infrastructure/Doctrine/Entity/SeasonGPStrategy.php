<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\Doctrine\Entity;

use App\Race\Infrastructure\Doctrine\Entity\Race;
use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonGPStrategyRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: DoctrineSeasonGPStrategyRepository::class)]
#[UniqueEntity(
    fields: ['participation', 'race'],
    message: 'A strategy already exists for this race.',
)]
class SeasonGPStrategy
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[ORM\ManyToOne(targetEntity: SeasonParticipation::class, inversedBy: 'gpStrategies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SeasonParticipation $participation = null;

    #[ORM\ManyToOne(targetEntity: Race::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Race $race = null;

    #[ORM\ManyToOne(targetEntity: SeasonRosterDriver::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?SeasonRosterDriver $driver1 = null;

    #[ORM\ManyToOne(targetEntity: SeasonRosterDriver::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?SeasonRosterDriver $driver2 = null;

    #[ORM\ManyToOne(targetEntity: SeasonRosterTeam::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?SeasonRosterTeam $team = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $points = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $locked = false;

    #[ORM\OneToMany(mappedBy: 'gpStrategy', targetEntity: SeasonBonusUsage::class, cascade: ['persist', 'remove'])]
    private Collection $bonusUsages;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
        $this->bonusUsages = new ArrayCollection();
    }

    public function getParticipation(): ?SeasonParticipation
    {
        return $this->participation;
    }

    public function setParticipation(SeasonParticipation $participation): static
    {
        $this->participation = $participation;

        return $this;
    }

    public function getRace(): ?Race
    {
        return $this->race;
    }

    public function getRaceUuid(): ?string
    {
        return $this->race?->getUuid();
    }

    public function setRace(Race $race): static
    {
        $this->race = $race;

        return $this;
    }

    public function getDriver1(): ?SeasonRosterDriver
    {
        return $this->driver1;
    }

    public function setDriver1(SeasonRosterDriver $driver): static
    {
        $this->driver1 = $driver;

        return $this;
    }

    public function getDriver2(): ?SeasonRosterDriver
    {
        return $this->driver2;
    }

    public function setDriver2(SeasonRosterDriver $driver): static
    {
        $this->driver2 = $driver;

        return $this;
    }

    public function getTeam(): ?SeasonRosterTeam
    {
        return $this->team;
    }

    public function setTeam(SeasonRosterTeam $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function isLocked(): bool
    {
        return $this->locked;
    }

    public function lock(): static
    {
        $this->locked = true;

        return $this;
    }

    public function getBonusUsages(): Collection
    {
        return $this->bonusUsages;
    }

    public function hasBonusOfType(string $type): bool
    {
        return $this->bonusUsages->exists(
            fn (int $k, SeasonBonusUsage $b) => $b->getBonusType()->value === $type
        );
    }
}
