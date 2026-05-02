<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\Doctrine\Entity;

use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonRosterRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: DoctrineSeasonRosterRepository::class)]
class SeasonRoster
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[ORM\OneToOne(inversedBy: 'roster', targetEntity: SeasonParticipation::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?SeasonParticipation $participation = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $budgetSpent = 0;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $validatedAt = null;

    #[ORM\OneToMany(mappedBy: 'roster', targetEntity: SeasonRosterDriver::class, cascade: ['persist', 'remove'])]
    private Collection $drivers;

    #[ORM\OneToMany(mappedBy: 'roster', targetEntity: SeasonRosterTeam::class, cascade: ['persist', 'remove'])]
    private Collection $teams;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
        $this->validatedAt = new \DateTimeImmutable();
        $this->drivers = new ArrayCollection();
        $this->teams = new ArrayCollection();
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

    public function getBudgetSpent(): int
    {
        return $this->budgetSpent;
    }

    public function setBudgetSpent(int $budgetSpent): static
    {
        $this->budgetSpent = $budgetSpent;

        return $this;
    }

    public function getValidatedAt(): ?\DateTimeImmutable
    {
        return $this->validatedAt;
    }

    public function getDrivers(): Collection
    {
        return $this->drivers;
    }

    public function addDriver(SeasonRosterDriver $driver): static
    {
        $driver->setRoster($this);
        $this->drivers[] = $driver;

        return $this;
    }

    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(SeasonRosterTeam $team): static
    {
        $team->setRoster($this);
        $this->teams[] = $team;

        return $this;
    }
}
