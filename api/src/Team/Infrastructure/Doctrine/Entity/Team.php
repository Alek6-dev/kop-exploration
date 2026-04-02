<?php

declare(strict_types=1);

namespace App\Team\Infrastructure\Doctrine\Entity;

use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\Performance\Domain\Model\TeamPerformanceInterface;
use App\Performance\Infrastructure\Doctrine\Entity\TeamPerformance;
use App\Result\Infrastructure\Doctrine\Entity\Result;
use App\Season\Infrastructure\Doctrine\Entity\SeasonTeam;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\ArchivableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\HasImageTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\HasMinValueTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\HasResultTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Infrastructure\Doctrine\Repository\DoctrineTeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: DoctrineTeamRepository::class)]
class Team implements TeamInterface
{
    use ArchivableTrait;
    use HasImageTrait;
    use HasMinValueTrait;
    use HasResultTrait;
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[Assert\CssColor(formats: 'hex_long')]
    #[ORM\Column(type: Types::STRING)]
    private ?string $color;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Driver::class)]
    private ?Collection $drivers;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: SeasonTeam::class)]
    private ?Collection $seasonTeams;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Result::class)]
    private ?Collection $results;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: TeamPerformance::class)]
    private ?Collection $performances;

    #[Vich\UploadableField(mapping: 'team', fileNameProperty: 'image')]
    protected ?File $imageFile = null;

    public function __construct()
    {
        $this->seasonTeams = new ArrayCollection();
        $this->drivers = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->performances = new ArrayCollection();
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getDrivers(): ?Collection
    {
        return $this->drivers;
    }

    public function addDriver(DriverInterface $driver): static
    {
        $driver->setTeam($this);
        $this->drivers[] = $driver;

        return $this;
    }

    public function removeDriver(DriverInterface $driver): void
    {
        $this->drivers->removeElement($driver);
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function getSeasonTeams(): ?Collection
    {
        return $this->seasonTeams;
    }

    public function getPerformances(): ?Collection
    {
        return $this->performances;
    }

    public function addPerformance(TeamPerformanceInterface $teamPerformance): static
    {
        $teamPerformance->setTeam($this);
        $this->performances[] = $teamPerformance;

        return $this;
    }

    public function removePerformance(TeamPerformanceInterface $teamPerformance): void
    {
        $this->performances->removeElement($teamPerformance);
    }

    public function getRelativeImagePath(): ?string
    {
        return 'uploads/images/team/'.$this->image;
    }
}
