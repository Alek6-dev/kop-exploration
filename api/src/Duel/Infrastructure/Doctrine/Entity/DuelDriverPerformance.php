<?php

declare(strict_types=1);

namespace App\Duel\Infrastructure\Doctrine\Entity;

use App\Duel\Domain\Model\DuelDriverPerformanceInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Duel\Infrastructure\Doctrine\Repository\DoctrineDuelDriverPerformanceRepository;
use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Performance\Infrastructure\Doctrine\Entity\DriverPerformance;
use App\Performance\Infrastructure\Doctrine\Entity\Trait\DriverPerformanceTrait;
use App\Race\Domain\Model\RaceInterface;
use App\Result\Domain\Model\ResultInterface;
use App\Season\Domain\Model\SeasonInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctrineDuelDriverPerformanceRepository::class)]
class DuelDriverPerformance implements DuelDriverPerformanceInterface
{
    use DriverPerformanceTrait;

    #[ORM\ManyToOne(targetEntity: Duel::class, inversedBy: 'driverPerformances')]
    private ?DuelInterface $duel = null;

    #[ORM\ManyToOne(targetEntity: DriverPerformance::class, inversedBy: 'duels')]
    private ?DriverPerformanceInterface $performanceReference = null;

    public function getResult(): ?ResultInterface
    {
        return $this->performanceReference->getResult();
    }

    public function setResult(ResultInterface $result): static
    {
        throw new \LogicException('Impossible to set result from duel');
    }

    public function getRace(): ?RaceInterface
    {
        return $this->duel->getRace();
    }

    public function setRace(RaceInterface $race): static
    {
        throw new \LogicException('Impossible to set race from duel');
    }

    public function getSeason(): ?SeasonInterface
    {
        return $this->getPerformanceReference()->getSeason();
    }

    public function setSeason(?SeasonInterface $season): static
    {
        throw new \LogicException('Impossible to set season from duel');
    }

    public function getDUel(): ?DuelInterface
    {
        return $this->duel;
    }

    public function setDuel(DuelInterface $duel): static
    {
        $this->duel = $duel;

        return $this;
    }

    public function getPerformanceReference(): ?DriverPerformanceInterface
    {
        return $this->performanceReference;
    }

    public function setPerformanceReference(DriverPerformanceInterface $performance): static
    {
        $this->performanceReference = $performance;

        return $this;
    }
}
