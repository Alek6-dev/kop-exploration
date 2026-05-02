<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\Doctrine\Entity;

use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonRosterDriverRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: DoctrineSeasonRosterDriverRepository::class)]
class SeasonRosterDriver
{
    use IdableTrait;
    use UuidableTrait;

    #[ORM\ManyToOne(targetEntity: SeasonRoster::class, inversedBy: 'drivers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SeasonRoster $roster = null;

    #[ORM\ManyToOne(targetEntity: Driver::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Driver $driver = null;

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

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(Driver $driver): static
    {
        $this->driver = $driver;

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
