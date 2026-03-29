<?php

declare(strict_types=1);

namespace App\Strategy\Infrastructure\Doctrine\Entity;

use App\Performance\Domain\Model\TeamPerformanceInterface;
use App\Performance\Infrastructure\Doctrine\Entity\TeamPerformance;
use App\Performance\Infrastructure\Doctrine\Entity\Trait\TeamPerformanceTrait;
use App\Race\Domain\Model\RaceInterface;
use App\Result\Domain\Model\ResultInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Domain\Model\StrategyTeamPerformanceInterface;
use App\Strategy\Infrastructure\Doctrine\Repository\DoctrineStrategyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctrineStrategyRepository::class)]
class StrategyTeamPerformance implements StrategyTeamPerformanceInterface
{
    use TeamPerformanceTrait;

    #[ORM\OneToOne(inversedBy: 'teamPerformance', targetEntity: Strategy::class)]
    public ?StrategyInterface $strategy = null;

    #[ORM\ManyToOne(targetEntity: TeamPerformance::class, inversedBy: 'strategies')]
    public ?TeamPerformanceInterface $performanceReference = null;

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

    public function getPerformanceReference(): ?TeamPerformanceInterface
    {
        return $this->performanceReference;
    }

    public function setPerformanceReference(TeamPerformanceInterface $performance): static
    {
        $this->performanceReference = $performance;

        return $this;
    }
}
