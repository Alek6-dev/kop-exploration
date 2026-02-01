<?php

declare(strict_types=1);

namespace App\Season\Infrastructure\Doctrine\Entity;

use App\Season\Domain\Model\SeasonInterface;
use App\Season\Domain\Model\SeasonTeamInterface;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonRaceRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineSeasonRaceRepository::class)]
class SeasonTeam implements SeasonTeamInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'seasonTeams')]
    private ?TeamInterface $team;
    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Season::class, inversedBy: 'seasonRaces')]
    private ?SeasonInterface $season;

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

    public function getSeason(): SeasonInterface
    {
        return $this->season;
    }

    public function setSeason(SeasonInterface $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->team->getName();
    }
}
