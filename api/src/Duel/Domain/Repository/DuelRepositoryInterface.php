<?php

declare(strict_types=1);

namespace App\Duel\Domain\Repository;

use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;
use App\User\Domain\Model\UserVisitorInterface;

interface DuelRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withRace(RaceInterface $race): static;

    public function withPlayer(PlayerInterface $player): static;

    public function withUserWin(UserVisitorInterface $user, bool $win = true): static;

    public function withStatus(ChampionshipRaceStatusEnum $status): static;

    public function withSeasonIsActive(): static;

    public function withLimitStrategyDate(\DateTimeImmutable $date): static;

    public function withChampionship(ChampionshipInterface $championship): static;
}
