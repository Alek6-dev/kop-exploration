<?php

declare(strict_types=1);

namespace App\Season\Infrastructure\Doctrine\Entity\Trait;

use App\Season\Domain\Model\SeasonInterface;
use App\Season\Infrastructure\Doctrine\Entity\Season;
use Doctrine\ORM\Mapping as ORM;

trait JoinSeasonTrait
{
    #[ORM\ManyToOne(targetEntity: Season::class)]
    private ?SeasonInterface $season = null;

    public function getSeason(): ?SeasonInterface
    {
        return $this->season;
    }

    public function setSeason(?SeasonInterface $season): static
    {
        $this->season = $season;

        return $this;
    }
}
