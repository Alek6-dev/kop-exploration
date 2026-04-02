<?php

declare(strict_types=1);

namespace App\Driver\Infrastructure\Doctrine\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Infrastructure\Doctrine\Repository\DoctrineDriverRepository;
use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Performance\Infrastructure\Doctrine\Entity\DriverPerformance;
use App\Result\Infrastructure\Doctrine\Entity\Result;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\ArchivableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\HasImageTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\HasMinValueTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\HasResultTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: DoctrineDriverRepository::class)]
class Driver implements DriverInterface
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
    #[ApiProperty]
    private ?string $firstName = null;
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $lastName = null;

    #[Vich\UploadableField(mapping: 'driver', fileNameProperty: 'image')]
    protected ?File $imageFile = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    #[Assert\When(
        expression: 'this.getReplacedBy() != null',
        constraints: [new Assert\IsFalse()]
    )]
    private ?bool $isReplacement = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    #[Assert\When(
        expression: 'this.isReplacement() === true',
        constraints: [new Assert\IsFalse()]
    )]
    private ?bool $replacedPermanently = false;

    #[ORM\OneToMany(mappedBy: 'driver', targetEntity: Result::class)]
    private ?Collection $results;

    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'drivers')]
    private ?TeamInterface $team = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Assert\When(
        expression: 'this.isReplacement() === true',
        constraints: [new Assert\IsNull()]
    )]
    private ?\DateTimeImmutable $replacementDateStart = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Assert\When(
        expression: 'this.isReplacement() === true',
        constraints: [new Assert\IsNull()]
    )]
    private ?\DateTimeImmutable $replacementDateEnd = null;

    #[ORM\OneToOne(targetEntity: self::class)]
    #[Assert\When(
        expression: 'this.isReplacement() === true',
        constraints: [new Assert\IsNull()]
    )]
    private ?DriverInterface $replacedBy = null;

    #[ORM\OneToMany(mappedBy: 'driver', targetEntity: DriverPerformance::class)]
    private ?Collection $performances;

    public function __construct()
    {
        $this->results = new ArrayCollection();
        $this->performances = new ArrayCollection();
        $this->uuid = (string) new UuidV4();
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getName(): string
    {
        return substr($this->getFirstName(), 0, 1).'. '.$this->getLastName();
    }

    public function isReplacement(): ?bool
    {
        return $this->isReplacement;
    }

    public function setIsReplacement(?bool $isReplacement): static
    {
        $this->isReplacement = $isReplacement;

        return $this;
    }

    public function getTeam(): ?TeamInterface
    {
        return $this->team;
    }

    public function setTeam(?TeamInterface $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getReplacementDateStart(): ?\DateTimeImmutable
    {
        return $this->replacementDateStart;
    }

    public function setReplacementDateStart(?\DateTimeImmutable $date): static
    {
        $this->replacementDateStart = $date;

        return $this;
    }

    public function getReplacementDateEnd(): ?\DateTimeImmutable
    {
        return $this->replacementDateEnd;
    }

    public function setReplacementDateEnd(?\DateTimeImmutable $date): static
    {
        $this->replacementDateEnd = $date;

        return $this;
    }

    public function __toString(): string
    {
        return $this->lastName.' '.$this->firstName;
    }

    public function getReplacedBy(): ?DriverInterface
    {
        return $this->replacedBy;
    }

    public function setReplacedBy(?DriverInterface $driver): static
    {
        $this->replacedBy = $driver;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->team?->getColor();
    }

    public function setColor(?string $color): static
    {
        $this->team->setColor($color);

        return $this;
    }

    public function getPerformances(): ?Collection
    {
        return $this->performances;
    }

    public function addPerformance(DriverPerformanceInterface $driverPerformance): static
    {
        $driverPerformance->setDriver($this);
        $this->performances[] = $driverPerformance;

        return $this;
    }

    public function removePerformance(DriverPerformanceInterface $driverPerformance): void
    {
        $this->performances->removeElement($driverPerformance);
    }

    public function getRelativeImagePath(): ?string
    {
        return 'uploads/images/driver/'.$this->image;
    }

    public function getCurrentlyReplacedBy(): ?DriverInterface
    {
        $currentDate = new \DateTimeImmutable();

        if ($this->getReplacementDateStart() <= $currentDate
            && ($this->getReplacementDateEnd() >= $currentDate || null === $this->getReplacementDateEnd())) {
            return $this->getReplacedBy();
        }

        return null;
    }

    public function setReplacedPermanently(?bool $replacedPermanently): static
    {
        $this->replacedPermanently = $replacedPermanently;

        return $this;
    }

    public function isReplacedPermanently(): bool
    {
        return (bool) $this->replacedPermanently;
    }
}
