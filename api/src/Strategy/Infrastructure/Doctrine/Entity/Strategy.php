<?php

declare(strict_types=1);

namespace App\Strategy\Infrastructure\Doctrine\Entity;

use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Bonus\Infrastructure\Doctrine\Entity\BonusApplication;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Infrastructure\Doctrine\Entity\Trait\JoinChampionshipTrait;
use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\Performance\Infrastructure\Doctrine\Entity\Trait\PerformanceTrait;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use App\Race\Infrastructure\Doctrine\Entity\Trait\JoinRaceTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\Strategy\Domain\Model\StrategyDriverPerformanceInterface;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Domain\Model\StrategyTeamPerformanceInterface;
use App\Strategy\Infrastructure\Doctrine\Repository\DoctrineStrategyRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: DoctrineStrategyRepository::class)]
class Strategy implements StrategyInterface
{
    use IdableTrait;
    use JoinChampionshipTrait;
    use JoinRaceTrait;
    use PerformanceTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[ORM\ManyToOne(targetEntity: Player::class)]
    public ?PlayerInterface $player = null;

    #[ORM\ManyToOne(targetEntity: Driver::class)]
    public ?DriverInterface $driver = null;

    #[ORM\OneToMany(mappedBy: 'strategy', targetEntity: StrategyDriverPerformance::class)]
    public ?Collection $driverPerformances;

    #[ORM\OneToOne(mappedBy: 'strategy', targetEntity: StrategyTeamPerformance::class)]
    public ?StrategyTeamPerformanceInterface $teamPerformance;

    #[ORM\OneToOne(mappedBy: 'strategy', targetEntity: BonusApplication::class)]
    public ?BonusApplicationInterface $bonusApplication = null;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getPlayer(): ?PlayerInterface
    {
        return $this->player;
    }

    public function setPlayer(PlayerInterface $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getDriver(): ?DriverInterface
    {
        return $this->driver;
    }

    public function setDriver(?DriverInterface $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getDriverPerformances(): ?Collection
    {
        return $this->driverPerformances;
    }

    public function addDriverPerformance(StrategyDriverPerformanceInterface $performance): static
    {
        $performance->setStrategy($this);
        $this->driverPerformances[] = $performance;

        return $this;
    }

    public function removeDriverPerformance(StrategyDriverPerformanceInterface $performance): void
    {
        $this->driverPerformances->removeElement($performance);
    }

    public function getTeamPerformance(): ?StrategyTeamPerformanceInterface
    {
        return $this->teamPerformance;
    }

    public function setTeamPerformance(StrategyTeamPerformanceInterface $performance): static
    {
        $this->teamPerformance = $performance;

        return $this;
    }

    public function isActive(): bool
    {
        return ChampionshipStatusEnum::ACTIVE === $this->championship->getStatus() && $this->championship->getCurrentChampionshipRace()?->getRace() === $this->race;
    }

    public function getBonusApplication(): ?BonusApplicationInterface
    {
        return $this->bonusApplication;
    }
}
