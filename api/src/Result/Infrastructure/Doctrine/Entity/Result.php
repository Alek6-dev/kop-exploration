<?php

declare(strict_types=1);

namespace App\Result\Infrastructure\Doctrine\Entity;

use App\Performance\Infrastructure\Doctrine\Entity\DriverPerformance;
use App\Performance\Infrastructure\Doctrine\Entity\TeamPerformance;
use App\Race\Domain\Model\RaceInterface;
use App\Race\Infrastructure\Doctrine\Entity\Race;
use App\Result\Domain\Model\ResultInterface;
use App\Result\Domain\Model\ResultLapInterface;
use App\Result\Infrastructure\Doctrine\Repository\DoctrineResultRepository;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Infrastructure\Doctrine\Entity\Season;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\ArchivableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineResultRepository::class)]
#[UniqueEntity(
    fields: ['season', 'race'],
    message: 'This race has already a result for this season.',
    errorPath: 'race',
)]
class Result implements ResultInterface
{
    use ArchivableTrait;
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Season::class, inversedBy: 'results')]
    private ?SeasonInterface $season;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Race::class, inversedBy: 'results')]
    private ?RaceInterface $race;

    #[ORM\OneToMany(mappedBy: 'result', targetEntity: ResultLap::class, cascade: ['persist', 'remove'])]
    private Collection $resultLaps;

    #[ORM\OneToMany(mappedBy: 'result', targetEntity: DriverPerformance::class, cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['points' => 'DESC', 'score' => 'DESC'])]
    private Collection $driverPerformances;

    #[ORM\OneToMany(mappedBy: 'result', targetEntity: TeamPerformance::class, cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['points' => 'DESC', 'score' => 'DESC'])]
    private Collection $teamPerformances;

    public function __construct()
    {
        $this->resultLaps = new ArrayCollection();
        $this->driverPerformances = new ArrayCollection();
        $this->teamPerformances = new ArrayCollection();
        $this->uuid = (string) new UuidV4();
    }

    public function getSeason(): ?SeasonInterface
    {
        return $this->season;
    }

    public function setSeason(?SeasonInterface $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function getRace(): ?RaceInterface
    {
        return $this->race;
    }

    public function setRace(?RaceInterface $race): static
    {
        $this->race = $race;

        return $this;
    }

    public function getResultLaps(): Collection
    {
        return $this->resultLaps;
    }

    public function addResultLap(ResultLapInterface $resultLap): static
    {
        $resultLap->setResult($this);
        $this->resultLaps[] = $resultLap;

        return $this;
    }

    public function removeResultLap(ResultLapInterface $resultLap): void
    {
        $this->resultLaps->removeElement($resultLap);
    }

    public function __toString(): string
    {
        return $this->getSeason().' '.$this->getRace();
    }

    public function getDriverPerformances(): ?Collection
    {
        return $this->driverPerformances;
    }

    public function addDriverPerformance(DriverPerformance $driverPerformance): static
    {
        $driverPerformance->setResult($this);
        $this->driverPerformances[] = $driverPerformance;

        return $this;
    }

    public function removeDriverPerformance(DriverPerformance $driverPerformance): void
    {
        $this->driverPerformances->removeElement($driverPerformance);
    }

    public function getTeamPerformances(): ?Collection
    {
        return $this->teamPerformances;
    }

    public function addTeamPerformance(TeamPerformance $teamPerformance): static
    {
        $teamPerformance->setResult($this);
        $this->teamPerformances[] = $teamPerformance;

        return $this;
    }

    public function removeTeamPerformance(TeamPerformance $teamPerformance): void
    {
        $this->teamPerformances->removeElement($teamPerformance);
    }
}
