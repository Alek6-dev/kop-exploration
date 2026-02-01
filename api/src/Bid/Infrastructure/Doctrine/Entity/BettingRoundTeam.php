<?php

declare(strict_types=1);

namespace App\Bid\Infrastructure\Doctrine\Entity;

use App\Bid\Domain\Model\BettingRoundInterface;
use App\Bid\Domain\Model\BettingRoundTeamInterface;
use App\Bid\Infrastructure\Doctrine\Entity\Trait\BettingRoundItemTrait;
use App\Bid\Infrastructure\Doctrine\Repository\DoctrineBettingRoundTeamRepository;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineBettingRoundTeamRepository::class)]
class BettingRoundTeam implements BettingRoundTeamInterface
{
    use BettingRoundItemTrait;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Team::class)]
    protected ?TeamInterface $team = null;

    #[Assert\NotNull]
    #[ORM\OneToOne(inversedBy: 'bettingRoundTeam', targetEntity: BettingRound::class)]
    protected ?BettingRoundInterface $bettingRound;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function setTeam(TeamInterface $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getTeam(): ?TeamInterface
    {
        return $this->team;
    }
}
