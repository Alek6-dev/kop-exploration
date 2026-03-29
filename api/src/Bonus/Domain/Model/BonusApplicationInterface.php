<?php

declare(strict_types=1);

namespace App\Bonus\Domain\Model;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Performance\Domain\Model\TeamPerformanceInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use App\Strategy\Domain\Model\StrategyInterface;

interface BonusApplicationInterface extends Idable, Uuidable, Timestampable
{
    public function getBonus(): ?BonusInterface;

    public function setBonus(BonusInterface $bonus): static;

    public function getTarget(): ?PlayerInterface;

    public function setTarget(PlayerInterface $player): static;

    public function getChampionship(): ?ChampionshipInterface;

    public function setChampionship(ChampionshipInterface $championship): static;

    public function getRace(): RaceInterface;

    public function setRace(RaceInterface $race): static;

    public function getPlayer(): ?PlayerInterface;

    public function setPlayer(PlayerInterface $player): static;

    public function getStrategy(): ?StrategyInterface;

    public function setStrategy(?StrategyInterface $strategy): static;

    public function getDuel(): ?DuelInterface;

    public function setDuel(?DuelInterface $duel): static;

    public function getBalanceBefore(): ?int;

    public function setBalanceBefore(?int $value): static;

    public function getBalanceAfter(): ?int;

    public function setBalanceAfter(?int $value): static;

    public function applyBonusToPerformance(DriverPerformanceInterface|TeamPerformanceInterface $performance): static;
}
