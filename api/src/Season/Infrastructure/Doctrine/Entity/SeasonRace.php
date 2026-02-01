<?php

declare(strict_types=1);

namespace App\Season\Infrastructure\Doctrine\Entity;

use App\Race\Domain\Model\RaceInterface;
use App\Race\Infrastructure\Doctrine\Entity\Race;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Domain\Model\SeasonRaceInterface;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonRaceRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: DoctrineSeasonRaceRepository::class)]
class SeasonRace implements SeasonRaceInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[ORM\ManyToOne(targetEntity: Race::class, inversedBy: 'seasonRaces')]
    private ?RaceInterface $race;
    #[ORM\ManyToOne(targetEntity: Season::class, inversedBy: 'seasonRaces')]
    private ?SeasonInterface $season;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $date;
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $qualificationDate;
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $sprintDate;
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $limitStrategyDate;

    #[ORM\Column(type: Types::INTEGER, length: 3)]
    private ?int $laps;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getRace(): ?RaceInterface
    {
        return $this->race;
    }

    public function setRace(RaceInterface $race): static
    {
        $this->race = $race;

        return $this;
    }

    public function getSeason(): ?SeasonInterface
    {
        return $this->season;
    }

    public function setSeason(SeasonInterface $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getQualificationDate(): ?\DateTimeImmutable
    {
        return $this->qualificationDate;
    }

    public function setQualificationDate(?\DateTimeImmutable $qualificationDate): static
    {
        $this->qualificationDate = $qualificationDate;

        return $this;
    }

    public function getSprintDate(): ?\DateTimeImmutable
    {
        return $this->sprintDate;
    }

    public function setSprintDate(?\DateTimeImmutable $sprintDate): static
    {
        $this->sprintDate = $sprintDate;

        return $this;
    }

    public function getLimitStrategyDate(): ?\DateTimeImmutable
    {
        return $this->limitStrategyDate;
    }

    public function setLimitStrategyDate(?\DateTimeImmutable $limitStrategyDate): static
    {
        $this->limitStrategyDate = $limitStrategyDate;

        return $this;
    }

    public function getLaps(): ?int
    {
        return $this->laps;
    }

    public function setLaps(?int $laps): static
    {
        $this->laps = $laps;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->race;
    }
}
