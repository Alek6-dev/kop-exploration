<?php

declare(strict_types=1);

namespace App\Championship\Domain\Model;

use App\Bid\Domain\Model\BidInterface;
use App\Championship\Domain\Enum\ChampionshipNumberPlayerEnum;
use App\Championship\Domain\Enum\ChampionshipNumberRaceEnum;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Duel\Domain\Model\DuelInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Stringifyable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use App\Strategy\Domain\Model\StrategyInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\Common\Collections\Collection;

interface ChampionshipInterface extends Stringifyable, Idable, Uuidable, Timestampable, BidInterface
{
    public function getName(): ?string;

    public function setName(?string $name): static;

    public function hasJokerEnabled(): ?bool;

    public function setJokerEnabled(?bool $jokerEnabled): static;

    public function setInvitationCode(?string $invitationCode): static;

    public function getInvitationCode(): ?string;

    public function getStatus(): ?ChampionshipStatusEnum;

    public function setStatus(?ChampionshipStatusEnum $status): static;

    public function getSeason(): ?SeasonInterface;

    public function setSeason(?SeasonInterface $season): static;

    public function getNumberOfRaces(): ?ChampionshipNumberRaceEnum;

    public function setNumberOfRaces(?ChampionshipNumberRaceEnum $numberOfRaces): static;

    public function getRegistrationEndDate(): ?\DateTimeImmutable;

    public function setRegistrationEndDate(?\DateTimeImmutable $date): static;

    public function getChampionshipRaces(): ?Collection;

    public function addChampionshipRace(ChampionshipRaceInterface $championshipRace): static;

    public function removeChampionshipRace(ChampionshipRaceInterface $championshipRace): void;

    public function getNumberOfPlayers(): ?ChampionshipNumberPlayerEnum;

    public function setNumberOfPlayers(?ChampionshipNumberPlayerEnum $numberOfPlayers): static;

    /**
     * @return Collection<int, PlayerInterface>|null
     */
    public function getPlayers(): ?Collection;

    public function addPlayer(PlayerInterface $player): static;

    public function removePlayer(PlayerInterface $player): void;

    public function getCreatedBy(): ?UserVisitorInterface;

    public function setCreatedBy(?UserVisitorInterface $user): static;

    /**
     * @return Collection<int, StrategyInterface>|null
     */
    public function getStrategies(): ?Collection;

    public function addStrategy(StrategyInterface $strategy): static;

    public function removeStrategy(StrategyInterface $strategy): void;

    /**
     * @return Collection<int, DuelInterface>|null
     */
    public function getDuels(): ?Collection;

    public function addDuel(DuelInterface $duel): static;

    public function removeDuel(DuelInterface $duel): void;

    public function isPlayer(UserVisitorInterface $user): bool;

    public function getPlayer(UserVisitorInterface $user): ?PlayerInterface;

    public function isCreator(UserVisitorInterface $user): bool;

    public function setInitialBudget(int $initialBudget): static;

    public function getInitialBudget(): ?int;

    public function setInitialUsageDriver(int $initialUsageDriver): static;

    public function getInitialUsageDriver(): ?int;

    public function countPlayersWithBidOnCurrentRound(): int;

    public function countRacesOver(): int;

    public function getWaitingResultChampionshipRace(): ?ChampionshipRaceInterface;

    public function getResultProcessedChampionshipRace(): ?ChampionshipRaceInterface;

    public function getCurrentChampionshipRace(): ?ChampionshipRaceInterface;

    public function getActiveChampionshipRace(): ?ChampionshipRaceInterface;

    public function getNextChampionshipRace(): ?ChampionshipRaceInterface;

    public function getCurrentStrategies(RaceInterface $race): ?Collection;

    public function getCurrentDuels(RaceInterface $race): ?Collection;
}
