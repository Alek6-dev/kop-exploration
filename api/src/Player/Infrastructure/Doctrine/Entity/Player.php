<?php

declare(strict_types=1);

namespace App\Player\Infrastructure\Doctrine\Entity;

use App\Bid\Domain\Model\BettingRoundInterface;
use App\Bid\Infrastructure\Doctrine\Entity\BettingRound;
use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Bonus\Infrastructure\Doctrine\Entity\BonusApplication;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Infrastructure\Doctrine\Entity\Championship;
use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\Duel\Domain\Model\DuelInterface;
use App\Performance\Infrastructure\Doctrine\Entity\Trait\PerformanceTrait;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Infrastructure\Doctrine\Repository\DoctrinePlayerRepository;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Infrastructure\Doctrine\Entity\Strategy;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrinePlayerRepository::class)]
#[UniqueEntity(
    fields: ['championship', 'user'],
    message: 'This player is already participating in this championship',
    errorPath: 'user',
)]
#[UniqueEntity(
    fields: ['championship', 'team'],
    message: 'This team is already selected for this championship',
    errorPath: 'team',
)]
#[UniqueEntity(
    fields: ['championship', 'name'],
    message: 'This name is already used for this championship',
    errorPath: 'name',
)]
class Player implements PlayerInterface
{
    use IdableTrait;
    use PerformanceTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $remainingBudget = null;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    private ?TeamInterface $selectedTeam = null;

    #[ORM\ManyToOne(targetEntity: Driver::class)]
    private ?DriverInterface $selectedDriver1 = null;

    #[ORM\ManyToOne(targetEntity: Driver::class)]
    private ?DriverInterface $selectedDriver2 = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $remainingUsageDriver1 = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $remainingUsageDriver2 = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $remainingDuelUsageDriver1 = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $remainingDuelUsageDriver2 = null;

    #[ORM\ManyToOne(targetEntity: Championship::class, fetch: 'EAGER', inversedBy: 'players')]
    private ?ChampionshipInterface $championship;

    #[ORM\ManyToOne(targetEntity: UserVisitor::class, fetch: 'EAGER', inversedBy: 'players')]
    private ?UserVisitorInterface $user;

    #[Assert\Length(min: 3, max: 25)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: BettingRound::class)]
    private ?Collection $bettingRounds;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Strategy::class)]
    private ?Collection $strategies;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: BonusApplication::class)]
    #[ORM\OrderBy(['updatedAt' => 'DESC'])]
    private ?Collection $bonusUsages;

    #[ORM\OneToMany(mappedBy: 'target', targetEntity: BonusApplication::class)]
    #[ORM\OrderBy(['updatedAt' => 'DESC'])]
    private ?Collection $bonusTargets;

    public function __construct()
    {
        $this->bettingRounds = new ArrayCollection();
        $this->strategies = new ArrayCollection();
        $this->bonusUsages = new ArrayCollection();
        $this->bonusTargets = new ArrayCollection();
        $this->uuid = (string) new UuidV4();
    }

    public function getRemainingBudget(): ?int
    {
        return $this->remainingBudget;
    }

    public function setRemainingBudget(?int $remainingBudget): static
    {
        $this->remainingBudget = $remainingBudget;

        return $this;
    }

    public function getSelectedTeam(): ?TeamInterface
    {
        return $this->selectedTeam;
    }

    public function setSelectedTeam(?TeamInterface $team): static
    {
        $this->selectedTeam = $team;

        return $this;
    }

    public function getSelectedDriver1(): ?DriverInterface
    {
        return $this->selectedDriver1;
    }

    public function setSelectedDriver1(?DriverInterface $driver): static
    {
        $this->selectedDriver1 = $driver;

        return $this;
    }

    public function setRemainingUsageDriver1(?int $remainingUsage): static
    {
        $this->remainingUsageDriver1 = $remainingUsage;

        return $this;
    }

    public function getRemainingUsageDriver1(): ?int
    {
        return $this->remainingUsageDriver1;
    }

    public function setRemainingDuelUsageDriver1(?int $remainingUsage): static
    {
        $this->remainingDuelUsageDriver1 = $remainingUsage;

        return $this;
    }

    public function getRemainingDuelUsageDriver1(): ?int
    {
        return $this->remainingDuelUsageDriver1;
    }

    public function getSelectedDriver2(): ?DriverInterface
    {
        return $this->selectedDriver2;
    }

    public function setSelectedDriver2(?DriverInterface $driver): static
    {
        $this->selectedDriver2 = $driver;

        return $this;
    }

    public function setRemainingUsageDriver2(?int $remainingUsage): static
    {
        $this->remainingUsageDriver2 = $remainingUsage;

        return $this;
    }

    public function getRemainingUsageDriver2(): ?int
    {
        return $this->remainingUsageDriver2;
    }

    public function getRemainingDuelUsageDriver2(): ?int
    {
        return $this->remainingDuelUsageDriver2;
    }

    public function setRemainingDuelUsageDriver2(?int $remainingUsage): static
    {
        $this->remainingDuelUsageDriver2 = $remainingUsage;

        return $this;
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

    public function getUser(): ?UserVisitorInterface
    {
        return $this->user;
    }

    public function setUser(?UserVisitorInterface $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->user?->getPseudo();
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getBettingRounds(): ?Collection
    {
        return $this->bettingRounds;
    }

    public function addBettingRound(BettingRoundInterface $bettingRound): void
    {
        $bettingRound->setPlayer($this);
        $this->bettingRounds[] = $bettingRound;
    }

    public function removeBettingRound(BettingRoundInterface $bettingRound): void
    {
        $this->bettingRounds->removeElement($bettingRound);
    }

    public function haveBidOnRound(int|ChampionshipInterface $championship): bool
    {
        if ($championship instanceof ChampionshipInterface) {
            $championship = (int) $championship->getCurrentRound();
        }
        if (0 >= $championship || !$this->bettingRounds->count()) {
            return false;
        }

        $bettingRounds = $this->bettingRounds->filter(fn (BettingRoundInterface $bettingRound) => $championship === $bettingRound->getRound());

        return (bool) $bettingRounds->count();
    }

    public function getLastBettingRound(): ?BettingRoundInterface
    {
        return $this->bettingRounds->findFirst(fn (int $key, BettingRoundInterface $bettingRound) => $this->championship->getCurrentRound() === $bettingRound->getRound());
    }

    public function getBettingRound(int $round): ?BettingRoundInterface
    {
        return $this->bettingRounds->findFirst(fn (int $key, BettingRoundInterface $bettingRound) => $round === $bettingRound->getRound());
    }

    public function isAfk(): bool
    {
        if (2 > $this->championship->getCurrentRound()) {
            return false;
        }

        $lastBettingRoundCount = $this->bettingRounds
            ->filter(fn (BettingRoundInterface $bettingRound) => $this->championship->getCurrentRound() === $bettingRound->getRound() || $this->championship->getCurrentRound() - 1 === $bettingRound->getRound())
            ->count();

        return 2 < $lastBettingRoundCount;
    }

    public function getStrategies(): ?Collection
    {
        return $this->strategies;
    }

    public function addStrategy(StrategyInterface $strategy): static
    {
        $strategy->setPlayer($this);
        $this->strategies[] = $strategy;

        return $this;
    }

    public function removeStrategy(StrategyInterface $strategy): void
    {
        $this->strategies->removeElement($strategy);
    }

    public function getCurrentStrategy(): ?StrategyInterface
    {
        return $this->strategies->findFirst(fn (int $key, StrategyInterface $strategy) => $strategy->getRace() === $this->getChampionship()->getCurrentChampionshipRace()?->getRace());
    }

    public function getDuels(): ?Collection
    {
        return $this->championship->getDuels()->filter(fn (DuelInterface $duel) => $duel->getPlayer1() === $this || $duel->getPlayer2() === $this);
    }

    public function getCurrentDuel(): ?DuelInterface
    {
        return $this->getDuels()->findFirst(fn (int $key, DuelInterface $duel) => $duel->getRace() === $this->getChampionship()->getCurrentChampionshipRace()?->getRace());
    }

    public function getActiveSelectedDriver1(): ?DriverInterface
    {
        return $this->getActiveDriver($this->getSelectedDriver1());
    }

    public function getActiveSelectedDriver2(): ?DriverInterface
    {
        return $this->getActiveDriver($this->getSelectedDriver2());
    }

    public function getBonusUsages(): ?Collection
    {
        return $this->bonusUsages;
    }

    public function getBonusTargets(): ?Collection
    {
        return $this->bonusTargets;
    }

    public function getBonusUsagesOnRace(RaceInterface $race): ?Collection
    {
        return $this->bonusUsages->filter(fn (BonusApplicationInterface $bonusApplication) => $race === $bonusApplication->getDuel()?->getRace() || $race === $bonusApplication->getStrategy()?->getRace());
    }

    public function getStrategyBonusesTargetingCurrentPlayerOnRace(RaceInterface $race): ?Collection
    {
        return $this->bonusTargets->filter(fn (BonusApplicationInterface $bonusApplication) => $race === $bonusApplication->getStrategy()?->getRace());
    }

    public function getDuelBonusesTargetingCurrentPlayerOnRace(RaceInterface $race): ?Collection
    {
        return $this->bonusTargets->filter(fn (BonusApplicationInterface $bonusApplication) => $race === $bonusApplication->getDuel()?->getRace());
    }

    private function getActiveDriver(DriverInterface $driver): ?DriverInterface
    {
        return $driver->getCurrentlyReplacedBy() ?? $driver;
    }
}
