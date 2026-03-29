<?php

declare(strict_types=1);

namespace App\Bid\Domain\Model;

use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use Doctrine\Common\Collections\Collection;

interface BidInterface extends Idable, Uuidable, Timestampable
{
    public function setCurrentRound(int $currentRound): static;

    public function getCurrentRound(): ?int;

    public function setCurrentRoundEndDate(\DateTimeImmutable $date): static;

    public function getCurrentRoundEndDate(): ?\DateTimeImmutable;

    public function addBettingRoundPlayer(BettingRoundInterface $bettingRound): void;

    public function removeBettingRound(BettingRoundInterface $bettingRound): void;

    public function getBettingRounds(): ?Collection;
}
