<?php

declare(strict_types=1);

namespace App\Bid\Domain\Model;

use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use Doctrine\Common\Collections\Collection;

interface BettingRoundInterface extends Idable, Uuidable, Timestampable
{
    public function getPlayer(): ?PlayerInterface;

    public function setPlayer(PlayerInterface $player): static;

    public function setIsSetBySystem(bool $isSetBySystem): static;

    public function isSetBySystem(): ?bool;

    public function getRound(): ?int;

    public function setRound(int $round): static;

    public function getBettingRoundDrivers(): ?Collection;

    public function addBettingRoundDriver(BettingRoundDriverInterface $bettingRoundDriver): void;

    public function removeBettingRoundDriver(BettingRoundDriverInterface $bettingRoundDriver): void;

    public function getBettingRoundTeam(): ?BettingRoundTeamInterface;

    public function setBettingRoundTeam(BettingRoundTeamInterface $bettingRoundTeam): static;
}
