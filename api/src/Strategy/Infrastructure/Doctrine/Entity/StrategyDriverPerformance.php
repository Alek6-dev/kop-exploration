<?php

declare(strict_types=1);

namespace App\Strategy\Infrastructure\Doctrine\Entity;

use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Performance\Infrastructure\Doctrine\Entity\DriverPerformance;
use App\Performance\Infrastructure\Doctrine\Entity\Trait\DriverPerformanceTrait;
use App\Race\Domain\Model\RaceInterface;
use App\Result\Domain\Model\ResultInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Strategy\Domain\Model\StrategyDriverPerformanceInterface;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Infrastructure\Doctrine\Repository\DoctrineStrategyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctrineStrategyRepository::class)]
class StrategyDriverPerformance implements StrategyDriverPerformanceInterface
{
    use DriverPerformanceTrait;

    #[ORM\ManyToOne(targetEntity: Strategy::class, inversedBy: 'driverPerformances')]
    public ?StrategyInterface $strategy = null;

    #[ORM\ManyToOne(targetEntity: DriverPerformance::class, inversedBy: 'strategies')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    public ?DriverPerformanceInterface $performanceReference = null;

    public function getResult(): ?ResultInterface
    {
        return $this->performanceReference->getResult();
    }

    public function setResult(ResultInterface $result): static
    {
        throw new \LogicException('Impossible to set result from strategy');
    }

    public function getRace(): ?RaceInterface
    {
        return $this->strategy->getRace();
    }

    public function setRace(RaceInterface $race): static
    {
        throw new \LogicException('Impossible to set race from strategy');
    }

    public function getSeason(): ?SeasonInterface
    {
        return $this->strategy->getChampionship()->getSeason();
    }

    public function setSeason(?SeasonInterface $season): static
    {
        throw new \LogicException('Impossible to set season from strategy');
    }

    public function getStrategy(): ?StrategyInterface
    {
        return $this->strategy;
    }

    public function setStrategy(StrategyInterface $strategy): static
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function getPerformanceReference(): DriverPerformanceInterface
    {
        return $this->performanceReference;
    }

    public function setPerformanceReference(DriverPerformanceInterface $performance): static
    {
        $this->performanceReference = $performance;

        return $this;
    }
}
