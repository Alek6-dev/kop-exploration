<?php

declare(strict_types=1);

namespace App\Bid\Infrastructure\Doctrine\Entity;

use App\Bid\Domain\Model\BettingRoundDriverInterface;
use App\Bid\Domain\Model\BettingRoundInterface;
use App\Bid\Domain\Model\BettingRoundTeamInterface;
use App\Bid\Infrastructure\Doctrine\Repository\DoctrineBettingRoundRepository;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineBettingRoundRepository::class)]
class BettingRound implements BettingRoundInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Player::class)]
    private ?PlayerInterface $player = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $isSetBySystem = null;

    #[Assert\Positive]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $round = null;

    #[ORM\OneToMany(mappedBy: 'bettingRound', targetEntity: BettingRoundDriver::class, cascade: ['persist', 'remove'])]
    private ?Collection $bettingRoundDrivers;

    #[ORM\OneToOne(mappedBy: 'bettingRound', targetEntity: BettingRoundTeam::class, cascade: ['persist', 'remove'])]
    private ?BettingRoundTeamInterface $bettingRoundTeam;

    public function __construct()
    {
        $this->bettingRoundDrivers = new ArrayCollection();
        $this->uuid = (string) new UuidV4();
    }

    public function getPlayer(): ?PlayerInterface
    {
        return $this->player;
    }

    public function setPlayer(PlayerInterface $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function setIsSetBySystem(bool $isSetBySystem): static
    {
        $this->isSetBySystem = $isSetBySystem;

        return $this;
    }

    public function isSetBySystem(): ?bool
    {
        return $this->isSetBySystem;
    }

    public function getRound(): ?int
    {
        return $this->round;
    }

    public function setRound(int $round): static
    {
        $this->round = $round;

        return $this;
    }

    public function getBettingRoundDrivers(): ?Collection
    {
        return $this->bettingRoundDrivers;
    }

    public function addBettingRoundDriver(BettingRoundDriverInterface $bettingRoundDriver): void
    {
        $bettingRoundDriver->setBettingRound($this);
        $this->bettingRoundDrivers[] = $bettingRoundDriver;
    }

    public function removeBettingRoundDriver(BettingRoundDriverInterface $bettingRoundDriver): void
    {
        $this->bettingRoundDrivers->removeElement($bettingRoundDriver);
    }

    public function getBettingRoundTeam(): ?BettingRoundTeamInterface
    {
        return $this->bettingRoundTeam;
    }

    public function setBettingRoundTeam(BettingRoundTeamInterface $bettingRoundTeam): static
    {
        $bettingRoundTeam->setBettingRound($this);
        $this->bettingRoundTeam = $bettingRoundTeam;

        return $this;
    }
}
