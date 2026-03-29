<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\Doctrine\Entity;

use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Model\ChampionshipRaceInterface;
use App\Championship\Infrastructure\Doctrine\Repository\DoctrineChampionshipRaceRepository;
use App\Race\Domain\Model\RaceInterface;
use App\Race\Infrastructure\Doctrine\Entity\Race;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineChampionshipRaceRepository::class)]
class ChampionshipRace implements ChampionshipRaceInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Championship::class, inversedBy: 'championshipRaces')]
    private ?ChampionshipInterface $championship = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Race::class)]
    private ?RaceInterface $race = null;

    #[Assert\Type(type: ChampionshipRaceStatusEnum::class)]
    #[ORM\Column(type: Types::INTEGER, length: 2, enumType: ChampionshipRaceStatusEnum::class)]
    private ChampionshipRaceStatusEnum $status;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getChampionship(): ?ChampionshipInterface
    {
        return $this->championship;
    }

    public function setChampionship(?ChampionshipInterface $championship): static
    {
        $this->championship = $championship;

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

    public function __toString(): string
    {
        return $this->getChampionship()?->getName().' '.$this->getRace()?->getName();
    }

    public function getStatus(): ?ChampionshipRaceStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ChampionshipRaceStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }
}
