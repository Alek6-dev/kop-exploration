<?php

declare(strict_types=1);

namespace App\Bid\Infrastructure\Doctrine\Entity\Trait;

use App\Bid\Domain\Model\BettingRoundInterface;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait BettingRoundItemTrait
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\Positive]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $bidAmount = null;

    public function setBettingRound(BettingRoundInterface $bettingRound): static
    {
        $this->bettingRound = $bettingRound;

        return $this;
    }

    public function getBettingRound(): ?BettingRoundInterface
    {
        return $this->bettingRound;
    }

    public function setBidAmount(int $bidAmount): static
    {
        $this->bidAmount = $bidAmount;

        return $this;
    }

    public function getBidAmount(): ?int
    {
        return $this->bidAmount;
    }
}
