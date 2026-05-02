<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\Doctrine\Entity;

use App\Season\Infrastructure\Doctrine\Entity\Season;
use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonParticipationRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: DoctrineSeasonParticipationRepository::class)]
#[UniqueEntity(
    fields: ['user', 'season'],
    message: 'Player already enrolled in this season.',
)]
class SeasonParticipation
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[ORM\ManyToOne(targetEntity: UserVisitor::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserVisitor $user = null;

    #[ORM\ManyToOne(targetEntity: Season::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Season $season = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $totalPoints = 0;

    #[ORM\Column(type: Types::INTEGER)]
    private int $walletBalance = 500;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $enrolledAt = null;

    #[ORM\OneToOne(mappedBy: 'participation', targetEntity: SeasonRoster::class, cascade: ['persist', 'remove'])]
    private ?SeasonRoster $roster = null;

    #[ORM\OneToMany(mappedBy: 'participation', targetEntity: SeasonGPStrategy::class, cascade: ['persist', 'remove'])]
    private Collection $gpStrategies;

    #[ORM\OneToMany(mappedBy: 'participation', targetEntity: SeasonBonusUsage::class, cascade: ['persist', 'remove'])]
    private Collection $bonusUsages;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
        $this->enrolledAt = new \DateTimeImmutable();
        $this->gpStrategies = new ArrayCollection();
        $this->bonusUsages = new ArrayCollection();
    }

    public function getUser(): ?UserVisitor
    {
        return $this->user;
    }

    public function setUser(UserVisitor $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(Season $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function getTotalPoints(): int
    {
        return $this->totalPoints;
    }

    public function addPoints(int $points): static
    {
        $this->totalPoints += $points;

        return $this;
    }

    public function getWalletBalance(): int
    {
        return $this->walletBalance;
    }

    public function debitWallet(int $amount): static
    {
        $this->walletBalance -= $amount;

        return $this;
    }

    public function creditWallet(int $amount): static
    {
        $this->walletBalance += $amount;

        return $this;
    }

    public function getEnrolledAt(): ?\DateTimeImmutable
    {
        return $this->enrolledAt;
    }

    public function getRoster(): ?SeasonRoster
    {
        return $this->roster;
    }

    public function setRoster(SeasonRoster $roster): static
    {
        $this->roster = $roster;

        return $this;
    }

    public function hasRoster(): bool
    {
        return null !== $this->roster;
    }

    public function getGpStrategies(): Collection
    {
        return $this->gpStrategies;
    }

    public function getStrategyForRace(string $raceUuid): ?SeasonGPStrategy
    {
        return $this->gpStrategies->findFirst(
            fn (int $k, SeasonGPStrategy $s) => $s->getRaceUuid() === $raceUuid
        );
    }

    public function getBonusUsages(): Collection
    {
        return $this->bonusUsages;
    }

    public function __toString(): string
    {
        return (string) $this->user?->getPseudo();
    }
}
