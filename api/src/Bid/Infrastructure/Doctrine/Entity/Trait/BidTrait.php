<?php

declare(strict_types=1);

namespace App\Bid\Infrastructure\Doctrine\Entity\Trait;

use App\Bid\Domain\Model\BettingRoundInterface;
use App\Bid\Infrastructure\Doctrine\Entity\BettingRound;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait BidTrait
{
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER)]
    private int $currentRound = 0;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $currentRoundEndDate = null;

    #[ORM\OneToMany(mappedBy: 'championship', targetEntity: BettingRound::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $bettingRounds;

    public function setCurrentRound(int $currentRound): static
    {
        $this->currentRound = $currentRound;

        return $this;
    }

    public function getCurrentRound(): ?int
    {
        return $this->currentRound;
    }

    public function setCurrentRoundEndDate(\DateTimeImmutable $date): static
    {
        $this->currentRoundEndDate = $date;

        return $this;
    }

    public function getCurrentRoundEndDate(): ?\DateTimeImmutable
    {
        return $this->currentRoundEndDate;
    }

    public function addBettingRoundPlayer(BettingRoundInterface $bettingRound): void
    {
        $this->bettingRounds->add($bettingRound);
    }

    public function removeBettingRound(BettingRoundInterface $bettingRound): void
    {
        $this->bettingRounds->removeElement($bettingRound);
    }

    public function getBettingRounds(): ?Collection
    {
        return $this->bettingRounds;
    }
}
