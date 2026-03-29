<?php

declare(strict_types=1);

namespace App\Race\Infrastructure\Doctrine\Entity\Trait;

use App\Race\Domain\Model\RaceInterface;
use App\Race\Infrastructure\Doctrine\Entity\Race;
use Doctrine\ORM\Mapping as ORM;

trait JoinRaceTrait
{
    #[ORM\ManyToOne(targetEntity: Race::class)]
    private ?RaceInterface $race = null;

    public function getRace(): ?RaceInterface
    {
        return $this->race;
    }

    public function setRace(RaceInterface $race): static
    {
        $this->race = $race;

        return $this;
    }
}
