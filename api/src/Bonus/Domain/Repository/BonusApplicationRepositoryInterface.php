<?php

declare(strict_types=1);

namespace App\Bonus\Domain\Repository;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;
use App\Strategy\Domain\Model\StrategyInterface;

interface BonusApplicationRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withPlayer(PlayerInterface $player): static;

    public function withStrategy(StrategyInterface $strategy): static;

    public function withDuel(DuelInterface $duel): static;

    public function withStrategyIsNull(): static;

    public function withDuelIsNull(): static;

    public function withTarget(PlayerInterface $player): static;

    public function withChampionship(ChampionshipInterface $championship): static;

    public function withRace(RaceInterface $race): static;

    public function withOrderBySort(string $direction = 'DESC'): static;

    public function withOrderByCreatedAt(string $direction = 'DESC'): static;
}
