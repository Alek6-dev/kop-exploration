<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\Doctrine\Entity;

use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonRosterTeamRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: DoctrineSeasonRosterTeamRepository::class)]
class SeasonRosterTeam
{
    use IdableTrait;
    use UuidableTrait;

    #[ORM\ManyToOne(targetEntity: SeasonRoster::class, inversedBy: 'teams')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SeasonRoster $roster = null;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $purchasePrice = 0;

    #[ORM\Column(type: Types::INTEGER)]
    private int $maxUsages = 0;

    #[ORM\Column(type: Types::INTEGER)]
    private int $usagesLeft = 0;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getRoster(): ?SeasonRoster
    {
        return $this->roster;
    }

    public function setRoster(SeasonRoster $roster): static
    {
        $this->roster = $roster;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getPurchasePrice(): int
    {
        return $this->purchasePrice;
    }

    public function setPurchasePrice(int $price): static
    {
        $this->purchasePrice = $price;

        return $this;
    }

    public function getMaxUsages(): int
    {
        return $this->maxUsages;
    }

    public function setMaxUsages(int $max): static
    {
        $this->maxUsages = $max;

        return $this;
    }

    public function getUsagesLeft(): int
    {
        return $this->usagesLeft;
    }

    public function setUsagesLeft(int $usages): static
    {
        $this->usagesLeft = $usages;

        return $this;
    }

    public function decrementUsage(): static
    {
        --$this->usagesLeft;

        return $this;
    }

    public function hasUsagesLeft(): bool
    {
        return $this->usagesLeft > 0;
    }
}
