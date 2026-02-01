<?php

declare(strict_types=1);

namespace App\Bid\Domain\Model;

use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;

interface BettingRoundItemInterface extends Idable, Uuidable, Timestampable
{
    public function setBettingRound(BettingRoundInterface $bettingRound): static;

    public function getBettingRound(): ?BettingRoundInterface;

    public function setBidAmount(int $bidAmount): static;

    public function getBidAmount(): ?int;
}
