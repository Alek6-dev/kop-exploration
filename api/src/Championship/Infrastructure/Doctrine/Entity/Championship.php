<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\Doctrine\Entity;

use App\Bid\Infrastructure\Doctrine\Entity\Trait\BidTrait;
use App\Championship\Domain\Enum\ChampionshipNumberPlayerEnum;
use App\Championship\Domain\Enum\ChampionshipNumberRaceEnum;
use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Model\ChampionshipRaceInterface;
use App\Championship\Infrastructure\Doctrine\Repository\DoctrineChampionshipRepository;
use App\Duel\Domain\Model\DuelInterface;
use App\Duel\Infrastructure\Doctrine\Entity\Duel;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Infrastructure\Doctrine\Entity\Season;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Infrastructure\Doctrine\Entity\Strategy;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineChampionshipRepository::class)]
class Championship implements ChampionshipInterface
{
    use BidTrait;
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\Length(min: 3, max: 30)]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $jokerEnabled = null;

    #[Assert\Type(type: ChampionshipNumberRaceEnum::class)]
    #[ORM\Column(type: Types::INTEGER, length: 2, enumType: ChampionshipNumberRaceEnum::class)]
    private ?ChampionshipNumberRaceEnum $numberOfRaces = null;

    #[Assert\Type(type: ChampionshipNumberPlayerEnum::class)]
    #[ORM\Column(type: Types::INTEGER, length: 2, enumType: ChampionshipNumberPlayerEnum::class)]
    private ?ChampionshipNumberPlayerEnum $numberOfPlayers = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private ?string $invitationCode = null;

    #[Assert\Type(type: ChampionshipStatusEnum::class)]
    #[ORM\Column(type: Types::INTEGER, length: 2, enumType: ChampionshipStatusEnum::class)]
    private ?ChampionshipStatusEnum $status;

    #[ORM\OneToMany(mappedBy: 'championship', targetEntity: ChampionshipRace::class, cascade: ['persist', 'remove'])]
    private ?Collection $championshipRaces;

    #[ORM\OneToMany(mappedBy: 'championship', targetEntity: Player::class, cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['points' => 'DESC', 'score' => 'DESC'])]
    private ?Collection $players;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Season::class)]
    private ?SeasonInterface $season = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $registrationEndDate = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: UserVisitor::class)]
    private ?UserVisitorInterface $createdBy = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $initialBudget = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $initialUsageDriver = null;

    #[ORM\OneToMany(mappedBy: 'championship', targetEntity: Strategy::class, cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['points' => 'DESC', 'score' => 'DESC'])]
    private Collection $strategies;

    #[ORM\OneToMany(mappedBy: 'championship', targetEntity: Duel::class, cascade: ['persist', 'remove'])]
    private Collection $duels;

    public function __construct()
    {
        $this->strategies = new ArrayCollection();
        $this->duels = new ArrayCollection();
        $this->championshipRaces = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->status = ChampionshipStatusEnum::CREATED;
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

    public function hasJokerEnabled(): ?bool
    {
        return $this->jokerEnabled;
    }

    public function setJokerEnabled(?bool $jokerEnabled): static
    {
        $this->jokerEnabled = $jokerEnabled;

        return $this;
    }

    public function getSeason(): ?SeasonInterface
    {
        return $this->season;
    }

    public function setSeason(?SeasonInterface $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function getNumberOfRaces(): ?ChampionshipNumberRaceEnum
    {
        return $this->numberOfRaces;
    }

    public function setNumberOfRaces(?ChampionshipNumberRaceEnum $numberOfRaces): static
    {
        $this->numberOfRaces = $numberOfRaces;

        return $this;
    }

    public function getChampionshipRaces(): ?Collection
    {
        return $this->championshipRaces;
    }

    public function addChampionshipRace(ChampionshipRaceInterface $championshipRace): static
    {
        $championshipRace->setChampionship($this);
        $this->championshipRaces[] = $championshipRace;

        return $this;
    }

    public function removeChampionshipRace(ChampionshipRaceInterface $championshipRace): void
    {
        $this->championshipRaces->removeElement($championshipRace);
    }

    public function getNumberOfPlayers(): ?ChampionshipNumberPlayerEnum
    {
        return $this->numberOfPlayers;
    }

    public function setNumberOfPlayers(?ChampionshipNumberPlayerEnum $numberOfPlayers): static
    {
        $this->numberOfPlayers = $numberOfPlayers;

        return $this;
    }

    public function getPlayers(): ?Collection
    {
        return $this->players;
    }

    public function addPlayer(PlayerInterface $player): static
    {
        $player->setChampionship($this);
        $this->players[] = $player;

        return $this;
    }

    public function removePlayer(PlayerInterface $player): void
    {
        $this->players->removeElement($player);
    }

    public function setInvitationCode(?string $invitationCode): static
    {
        $this->invitationCode = $invitationCode;

        return $this;
    }

    public function getInvitationCode(): ?string
    {
        return $this->invitationCode;
    }

    public function getRegistrationEndDate(): ?\DateTimeImmutable
    {
        return $this->registrationEndDate;
    }

    public function setRegistrationEndDate(?\DateTimeImmutable $date): static
    {
        $this->registrationEndDate = $date;

        return $this;
    }

    public function getStatus(): ?ChampionshipStatusEnum
    {
        return $this->status;
    }

    public function setStatus(?ChampionshipStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedBy(): ?UserVisitorInterface
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?UserVisitorInterface $user): static
    {
        $this->createdBy = $user;

        return $this;
    }

    public function isPlayer(UserVisitorInterface $user): bool
    {
        return $this->players->exists(fn (int $key, PlayerInterface $player) => $player->getUser() === $user);
    }

    public function getPlayer(UserVisitorInterface $user): ?PlayerInterface
    {
        return $this->players->findFirst(fn (int $key, PlayerInterface $player) => $player->getUser() === $user);
    }

    public function isCreator(UserVisitorInterface $user): bool
    {
        return $this->createdBy === $user;
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function setInitialBudget(int $initialBudget): static
    {
        $this->initialBudget = $initialBudget;

        return $this;
    }

    public function getInitialBudget(): ?int
    {
        return $this->initialBudget;
    }

    public function setInitialUsageDriver(int $initialUsageDriver): static
    {
        $this->initialUsageDriver = $initialUsageDriver;

        return $this;
    }

    public function getInitialUsageDriver(): ?int
    {
        return $this->initialUsageDriver;
    }

    public function countPlayersWithBidOnCurrentRound(): int
    {
        return $this->players
            ->filter(fn (PlayerInterface $player) => $player->haveBidOnRound($this))
            ->count();
    }

    public function countRacesOver(): int
    {
        return $this->championshipRaces
            ->filter(fn (ChampionshipRaceInterface $championshipRace) => ChampionshipRaceStatusEnum::OVER === $championshipRace->getStatus())
            ->count();
    }

    public function getStrategies(): ?Collection
    {
        return $this->strategies;
    }

    public function addStrategy(StrategyInterface $strategy): static
    {
        $strategy->setChampionship($this);

        $this->strategies[] = $strategy;

        return $this;
    }

    public function removeStrategy(StrategyInterface $strategy): void
    {
        $this->strategies->removeElement($strategy);
    }

    public function getDuels(): ?Collection
    {
        return $this->duels;
    }

    public function addDuel(DuelInterface $duel): static
    {
        $duel->setChampionship($this);

        $this->duels[] = $duel;

        return $this;
    }

    public function removeDuel(DuelInterface $duel): void
    {
        $this->duels->removeElement($duel);
    }

    public function getWaitingResultChampionshipRace(): ?ChampionshipRaceInterface
    {
        return $this->getChampionshipRaces()->findFirst(fn (int $key, ChampionshipRaceInterface $championshipRace) => ChampionshipRaceStatusEnum::WAITING_RESULT === $championshipRace->getStatus());
    }

    public function getResultProcessedChampionshipRace(): ?ChampionshipRaceInterface
    {
        return $this->getChampionshipRaces()->findFirst(fn (int $key, ChampionshipRaceInterface $championshipRace) => ChampionshipRaceStatusEnum::RESULT_PROCESSED === $championshipRace->getStatus());
    }

    public function getCurrentChampionshipRace(): ?ChampionshipRaceInterface
    {
        return $this->getChampionshipRaces()->findFirst(fn (int $key, ChampionshipRaceInterface $championshipRace) => \in_array($championshipRace->getStatus(), ChampionshipRaceStatusEnum::isActiveStatus()));
    }

    public function getActiveChampionshipRace(): ?ChampionshipRaceInterface
    {
        return $this->getChampionshipRaces()->findFirst(fn (int $key, ChampionshipRaceInterface $championshipRace) => ChampionshipRaceStatusEnum::ACTIVE === $championshipRace->getStatus());
    }

    public function getNextChampionshipRace(): ?ChampionshipRaceInterface
    {
        return $this->getChampionshipRaces()->findFirst(fn (int $key, ChampionshipRaceInterface $championshipRace) => ChampionshipRaceStatusEnum::CREATED === $championshipRace->getStatus());
    }

    public function getCurrentStrategies(RaceInterface $race): ?Collection
    {
        return $this->strategies->filter(fn (StrategyInterface $strategy) => $strategy->getRace() === $race);
    }

    public function getCurrentDuels(RaceInterface $race): ?Collection
    {
        return $this->duels->filter(fn (DuelInterface $duel) => $duel->getRace() === $race);
    }
}
