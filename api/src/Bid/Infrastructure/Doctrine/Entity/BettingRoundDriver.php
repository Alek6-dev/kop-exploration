<?php

declare(strict_types=1);

namespace App\Bid\Infrastructure\Doctrine\Entity;

use App\Bid\Domain\Model\BettingRoundDriverInterface;
use App\Bid\Domain\Model\BettingRoundInterface;
use App\Bid\Infrastructure\Doctrine\Entity\Trait\BettingRoundItemTrait;
use App\Bid\Infrastructure\Doctrine\Repository\DoctrineBettingRoundDriverRepository;
use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineBettingRoundDriverRepository::class)]
class BettingRoundDriver implements BettingRoundDriverInterface
{
    use BettingRoundItemTrait;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Driver::class)]
    protected ?DriverInterface $driver = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: BettingRound::class, inversedBy: 'bettingRoundDrivers')]
    protected ?BettingRoundInterface $bettingRound;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function setDriver(DriverInterface $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getDriver(): ?DriverInterface
    {
        return $this->driver;
    }
}
