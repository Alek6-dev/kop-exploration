<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\Doctrine\Entity;

use App\SeasonGame\Domain\Enum\SeasonBonusTypeEnum;
use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonBonusUsageRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: DoctrineSeasonBonusUsageRepository::class)]
class SeasonBonusUsage
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[ORM\ManyToOne(targetEntity: SeasonParticipation::class, inversedBy: 'bonusUsages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SeasonParticipation $participation = null;

    #[ORM\ManyToOne(targetEntity: SeasonGPStrategy::class, inversedBy: 'bonusUsages')]
    #[ORM\JoinColumn(nullable: true)]
    private ?SeasonGPStrategy $gpStrategy = null;

    #[ORM\Column(type: Types::STRING, enumType: SeasonBonusTypeEnum::class)]
    private ?SeasonBonusTypeEnum $bonusType = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $pricePaid = 0;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $usedAt = null;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getParticipation(): ?SeasonParticipation
    {
        return $this->participation;
    }

    public function setParticipation(SeasonParticipation $participation): static
    {
        $this->participation = $participation;

        return $this;
    }

    public function getGpStrategy(): ?SeasonGPStrategy
    {
        return $this->gpStrategy;
    }

    public function setGpStrategy(?SeasonGPStrategy $gpStrategy): static
    {
        $this->gpStrategy = $gpStrategy;

        return $this;
    }

    public function getBonusType(): ?SeasonBonusTypeEnum
    {
        return $this->bonusType;
    }

    public function setBonusType(SeasonBonusTypeEnum $bonusType): static
    {
        $this->bonusType = $bonusType;

        return $this;
    }

    public function getPricePaid(): int
    {
        return $this->pricePaid;
    }

    public function setPricePaid(int $price): static
    {
        $this->pricePaid = $price;

        return $this;
    }

    public function getUsedAt(): ?\DateTimeImmutable
    {
        return $this->usedAt;
    }

    public function markAsUsed(): static
    {
        $this->usedAt = new \DateTimeImmutable();

        return $this;
    }
}
