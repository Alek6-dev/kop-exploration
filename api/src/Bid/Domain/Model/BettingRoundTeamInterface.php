<?php

declare(strict_types=1);

namespace App\Bid\Domain\Model;

use App\Team\Domain\Model\TeamInterface;

interface BettingRoundTeamInterface extends BettingRoundItemInterface
{
    public function setTeam(TeamInterface $team): static;

    public function getTeam(): ?TeamInterface;
}
