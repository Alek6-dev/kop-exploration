<?php

declare(strict_types=1);

namespace App\Race\Infrastructure\Doctrine\Entity;

use App\Race\Domain\Enum\CountryEnum;
use App\Race\Domain\Model\RaceInterface;
use App\Race\Infrastructure\Doctrine\Repository\DoctrineRaceRepository;
use App\Season\Domain\Model\SeasonRaceInterface;
use App\Season\Infrastructure\Doctrine\Entity\SeasonRace;
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

#[ORM\Entity(repositoryClass: DoctrineRaceRepository::class)]
class Race implements RaceInterface
{
    use ArchivableTrait;
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[Assert\Type(type: CountryEnum::class)]
    #[ORM\Column(type: Types::STRING, length: 7, enumType: CountryEnum::class)]
    private ?CountryEnum $country = null;
    #[ORM\OneToMany(mappedBy: 'race', targetEntity: SeasonRace::class)]
    private Collection $seasonRaces;

    public function __construct()
    {
        $this->seasonRaces = new ArrayCollection();
        $this->uuid = (string) new UuidV4();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCountry(): ?CountryEnum
    {
        return $this->country;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setCountry(?CountryEnum $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getSeasonRaces(): ?Collection
    {
        return $this->seasonRaces;
    }

    public function addSeasonRace(SeasonRaceInterface $seasonRace): static
    {
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
