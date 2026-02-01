<?php

declare(strict_types=1);

namespace App\Performance\Infrastructure\Doctrine\Entity;

use App\Performance\Domain\Model\TeamPerformanceInterface;
use App\Performance\Infrastructure\Doctrine\Entity\Trait\TeamPerformanceTrait;
use App\Performance\Infrastructure\Doctrine\Repository\DoctrineTeamPerformanceRepository;
use App\Race\Infrastructure\Doctrine\Entity\Trait\JoinRaceTrait;
use App\Result\Domain\Model\ResultInterface;
use App\Result\Infrastructure\Doctrine\Entity\Result;
use App\Season\Infrastructure\Doctrine\Entity\Trait\JoinSeasonTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineTeamPerformanceRepository::class)]
class TeamPerformance implements TeamPerformanceInterface
{
    use JoinRaceTrait;
    use JoinSeasonTrait;
    use TeamPerformanceTrait;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Result::class, inversedBy: 'driverPerformances')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?ResultInterface $result = null;

    public function getResult(): ?ResultInterface
    {
        return $this->result;
    }

    public function setResult(?ResultInterface $result): static
    {
        $this->result = $result;

        return $this;
    }
}
