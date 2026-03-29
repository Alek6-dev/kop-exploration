<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\Doctrine\Entity\Trait;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Infrastructure\Doctrine\Entity\Championship;
use Doctrine\ORM\Mapping as ORM;

trait JoinChampionshipTrait
{
    #[ORM\ManyToOne(targetEntity: Championship::class)]
    private ?ChampionshipInterface $championship = null;

    public function getChampionship(): ?ChampionshipInterface
    {
        return $this->championship;
    }

    public function setChampionship(?ChampionshipInterface $championship): static
    {
        $this->championship = $championship;

        return $this;
    }
}
