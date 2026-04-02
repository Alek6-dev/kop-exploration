<?php

declare(strict_types=1);

namespace App\Season\Infrastructure\Doctrine\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Driver\Domain\Model\DriverInterface;
use App\Result\Infrastructure\Doctrine\Entity\Result;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Domain\Model\SeasonRaceInterface;
use App\Season\Domain\Model\SeasonTeamInterface;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\ArchivableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

// #[ApiResource]
#[ORM\Entity(repositoryClass: DoctrineSeasonRepository::class)]
class Season implements SeasonInterface
{
    use ArchivableTrait;
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $isActive = null;

    #[ORM\OneToMany(mappedBy: 'season', targetEntity: SeasonRace::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $seasonRaces;

    #[ORM\OneToMany(mappedBy: 'season', targetEntity: SeasonTeam::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $seasonTeams;

    #[ORM\OneToMany(mappedBy: 'season', targetEntity: Result::class)]
    private ?Collection $results;

    public function __construct()
    {
        $this->seasonRaces = new ArrayCollection();
        $this->seasonTeams = new ArrayCollection();
        $this->uuid = (string) new UuidV4();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getSeasonTeams(): ?Collection
    {
        return $this->seasonTeams;
    }

    public function getSeasonDrivers(): ?Collection
    {
        $drivers = new ArrayCollection();
        $this->seasonTeams->map(function (SeasonTeamInterface $seasonTeam) use (&$drivers) {
            $seasonTeam->getTeam()->getDrivers()->map(function (DriverInterface $driver) use (&$drivers) {
                $drivers->add($driver);
            });
        });

        return $drivers;
    }

    public function getSeasonActiveDrivers(): ?Collection
    {
        $drivers = new ArrayCollection();
        $this->seasonTeams->map(function (SeasonTeamInterface $seasonTeam) use (&$drivers) {
            $seasonTeam->getTeam()->getDrivers()->map(function (DriverInterface $driver) use (&$drivers) {
                if ($driver->getCurrentlyReplacedBy()) {
                    return;
                }
                $drivers->add($driver);
            });
        });

        return $drivers;
    }

    public function addSeasonTeam(SeasonTeamInterface $seasonTeam): static
    {
        $seasonTeam->setSeason($this);
        $this->seasonTeams[] = $seasonTeam;

        return $this;
    }

    public function removeSeasonTeam(SeasonTeamInterface $seasonTeam): void
    {
        $this->seasonTeams->removeElement($seasonTeam);
    }

    public function getSeasonRaces(): ?Collection
    {
        return $this->seasonRaces;
    }

    public function addSeasonRace(SeasonRaceInterface $seasonRace): static
    {
        $seasonRace->setSeason($this);
        $this->seasonRaces[] = $seasonRace;

        return $this;
    }

    public function removeSeasonRace(SeasonRaceInterface $seasonRace): void
    {
        $this->seasonRaces->removeElement($seasonRace);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
