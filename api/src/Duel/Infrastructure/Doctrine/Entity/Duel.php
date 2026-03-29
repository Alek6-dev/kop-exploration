<?php

declare(strict_types=1);

namespace App\Duel\Infrastructure\Doctrine\Entity;

use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Bonus\Infrastructure\Doctrine\Entity\BonusApplication;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Infrastructure\Doctrine\Entity\Trait\JoinChampionshipTrait;
use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\Duel\Domain\Model\DuelDriverPerformanceInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Duel\Infrastructure\Doctrine\Repository\DoctrineDuelRepository;
use App\Performance\Domain\Enum\DuelPositionPointEnum;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use App\Race\Domain\Model\RaceInterface;
use App\Race\Infrastructure\Doctrine\Entity\Trait\JoinRaceTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineDuelRepository::class)]
class Duel implements DuelInterface
{
    use IdableTrait;
    use JoinChampionshipTrait;
    use JoinRaceTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[ORM\ManyToOne(targetEntity: Player::class, cascade: ['persist'])]
    private ?PlayerInterface $player1 = null;

    #[ORM\ManyToOne(targetEntity: Player::class, cascade: ['persist'])]
    private ?PlayerInterface $player2 = null;

    #[ORM\ManyToOne(targetEntity: Driver::class)]
    private ?DriverInterface $playerDriver1 = null;

    #[ORM\ManyToOne(targetEntity: Driver::class)]
    private ?DriverInterface $playerDriver2 = null;

    #[ORM\OneToMany(mappedBy: 'duel', targetEntity: DuelDriverPerformance::class, cascade: ['persist', 'remove'])]
    private ?Collection $driverPerformances;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::INTEGER, length: 2, enumType: DuelPositionPointEnum::class)]
    private ?DuelPositionPointEnum $pointsPlayer1 = DuelPositionPointEnum::P_DEFAULT;

    #[Assert\NotNull]
    #[Assert\Length(max: 3)]
    #[ORM\Column(type: Types::INTEGER, length: 2, enumType: DuelPositionPointEnum::class)]
    private ?DuelPositionPointEnum $pointsPlayer2 = DuelPositionPointEnum::P_DEFAULT;

    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, length: 6, nullable: true)]
    private ?int $scorePlayer1 = 0;

    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, length: 6, nullable: true)]
    private ?int $scorePlayer2 = 0;

    #[ORM\OneToMany(mappedBy: 'duel', targetEntity: BonusApplication::class)]
    public ?Collection $bonusApplications = null;

    public function __construct()
    {
        $this->driverPerformances = new ArrayCollection();
        $this->bonusApplications = new ArrayCollection();
        $this->uuid = (string) new UuidV4();
    }

    public function getPlayer1(): ?PlayerInterface
    {
        return $this->player1;
    }

    public function setPlayer1(PlayerInterface $player): static
    {
        $this->player1 = $player;

        return $this;
    }

    public function getPlayer2(): ?PlayerInterface
    {
        return $this->player2;
    }

    public function setPlayer2(PlayerInterface $player): static
    {
        $this->player2 = $player;

        return $this;
    }

    public function getPlayerDriver1(): ?DriverInterface
    {
        return $this->playerDriver1;
    }

    public function setPlayerDriver1(?DriverInterface $driver): static
    {
        $this->playerDriver1 = $driver;

        return $this;
    }

    public function getPlayerDriver2(): ?DriverInterface
    {
        return $this->playerDriver2;
    }

    public function setPlayerDriver2(?DriverInterface $driver): static
    {
        $this->playerDriver2 = $driver;

        return $this;
    }

    public function getDriverPerformances(): ?Collection
    {
        return $this->driverPerformances;
    }

    public function addDriverPerformance(DuelDriverPerformanceInterface $performance): static
    {
        $performance->setDuel($this);
        $this->driverPerformances[] = $performance;

        return $this;
    }

    public function removeDriverPerformance(DuelDriverPerformanceInterface $performance): void
    {
        $this->driverPerformances->removeElement($performance);
    }

    public function getPointsPlayer1(): DuelPositionPointEnum
    {
        return $this->pointsPlayer1;
    }

    public function setPointsPlayer1(DuelPositionPointEnum $points): static
    {
        $this->pointsPlayer1 = $points;

        return $this;
    }

    public function getPointsPlayer2(): DuelPositionPointEnum
    {
        return $this->pointsPlayer2;
    }

    public function setPointsPlayer2(DuelPositionPointEnum $points): static
    {
        $this->pointsPlayer2 = $points;

        return $this;
    }

    public function getScorePlayer1(): ?int
    {
        return $this->scorePlayer1;
    }

    public function setScorePlayer1(int $score): static
    {
        $this->scorePlayer1 = $score;

        return $this;
    }

    public function getScorePlayer2(): ?int
    {
        return $this->scorePlayer2;
    }

    public function setScorePlayer2(int $score): static
    {
        $this->scorePlayer2 = $score;

        return $this;
    }

    public function isActive(): bool
    {
        return ChampionshipStatusEnum::ACTIVE === $this->championship->getStatus() && $this->championship->getCurrentChampionshipRace()?->getRace() === $this->getRace();
    }

    public function getBonusApplications(): ?Collection
    {
        return $this->bonusApplications;
    }

    public function getBonusApplicationByPlayer1OnRace(RaceInterface $race): ?BonusApplicationInterface
    {
        return $this->bonusApplications->findFirst(fn (int $key, BonusApplicationInterface $bonusApplication) => $this->getPlayer1() === $bonusApplication->getPlayer() && $race === $bonusApplication->getRace());
    }

    public function getBonusApplicationByPlayer2OnRace(RaceInterface $race): ?BonusApplicationInterface
    {
        return $this->bonusApplications->findFirst(fn (int $key, BonusApplicationInterface $bonusApplication) => $this->getPlayer2() === $bonusApplication->getPlayer() && $race === $bonusApplication->getRace());
    }

    public function getBonusAppliedToPlayer1OnRace(RaceInterface $race): ?Collection
    {
        return $this->bonusApplications->filter(fn (BonusApplicationInterface $bonusApplication) => $this->getPlayer1() === $bonusApplication->getTarget() && $race === $bonusApplication->getRace());
    }

    public function getBonusAppliedToPlayer2OnRace(RaceInterface $race): ?Collection
    {
        return $this->bonusApplications->filter(fn (BonusApplicationInterface $bonusApplication) => $this->getPlayer2() === $bonusApplication->getTarget() && $race === $bonusApplication->getRace());
    }
}
