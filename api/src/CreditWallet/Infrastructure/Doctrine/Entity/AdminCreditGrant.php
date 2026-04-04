<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\Doctrine\Entity;

use App\Championship\Infrastructure\Doctrine\Entity\Championship;
use App\CreditWallet\Domain\Enum\GrantTargetType;
use App\CreditWallet\Infrastructure\Doctrine\Repository\DoctrineAdminCreditGrantRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineAdminCreditGrantRepository::class)]
class AdminCreditGrant
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\Positive]
    #[ORM\Column(type: Types::INTEGER)]
    private int $amount = 0;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $isDeduction = false;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: Types::STRING)]
    private string $reason = '';

    #[ORM\Column(type: Types::STRING, enumType: GrantTargetType::class)]
    private GrantTargetType $targetType = GrantTargetType::ALL;

    #[ORM\ManyToOne(targetEntity: UserVisitor::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?UserVisitor $targetPlayer = null;

    #[ORM\ManyToOne(targetEntity: Championship::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Championship $targetChampionship = null;

    /** @var Collection<int, UserVisitor> */
    #[ORM\ManyToMany(targetEntity: UserVisitor::class)]
    #[ORM\JoinTable(name: 'admin_credit_grant_excluded_players')]
    private Collection $excludedPlayers;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $executedAt = null;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
        $this->excludedPlayers = new ArrayCollection();
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function isDeduction(): bool
    {
        return $this->isDeduction;
    }

    public function setIsDeduction(bool $isDeduction): static
    {
        $this->isDeduction = $isDeduction;

        return $this;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function getTargetType(): GrantTargetType
    {
        return $this->targetType;
    }

    public function setTargetType(GrantTargetType|string $targetType): static
    {
        $this->targetType = $targetType instanceof GrantTargetType
            ? $targetType
            : GrantTargetType::from($targetType);

        return $this;
    }

    public function getTargetPlayer(): ?UserVisitor
    {
        return $this->targetPlayer;
    }

    public function setTargetPlayer(?UserVisitor $targetPlayer): static
    {
        $this->targetPlayer = $targetPlayer;

        return $this;
    }

    public function getTargetChampionship(): ?Championship
    {
        return $this->targetChampionship;
    }

    public function setTargetChampionship(?Championship $targetChampionship): static
    {
        $this->targetChampionship = $targetChampionship;

        return $this;
    }

    /** @return Collection<int, UserVisitor> */
    public function getExcludedPlayers(): Collection
    {
        return $this->excludedPlayers;
    }

    public function addExcludedPlayer(UserVisitor $user): static
    {
        if (!$this->excludedPlayers->contains($user)) {
            $this->excludedPlayers->add($user);
        }

        return $this;
    }

    public function removeExcludedPlayer(UserVisitor $user): static
    {
        $this->excludedPlayers->removeElement($user);

        return $this;
    }

    public function getExecutedAt(): ?\DateTimeImmutable
    {
        return $this->executedAt;
    }

    public function setExecutedAt(?\DateTimeImmutable $executedAt): static
    {
        $this->executedAt = $executedAt;

        return $this;
    }

    public function isExecuted(): bool
    {
        return null !== $this->executedAt;
    }

    public function __toString(): string
    {
        return sprintf('%s — %d crédits (%s)', $this->reason, $this->amount, $this->targetType->value);
    }
}
