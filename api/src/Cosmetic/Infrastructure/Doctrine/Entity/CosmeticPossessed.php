<?php

declare(strict_types=1);

namespace App\Cosmetic\Infrastructure\Doctrine\Entity;

use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Cosmetic\Domain\Model\CosmeticPossessedInterface;
use App\Cosmetic\Infrastructure\Doctrine\Repository\DoctrineCosmeticPossessedRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: DoctrineCosmeticPossessedRepository::class)]
class CosmeticPossessed implements CosmeticPossessedInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[ORM\ManyToOne(targetEntity: UserVisitor::class, inversedBy: 'cosmeticsPossessed')]
    private UserVisitorInterface $user;

    #[ORM\ManyToOne(targetEntity: Cosmetic::class)]
    private CosmeticInterface $cosmetic;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isSelected = false;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER)]
    private int $price = 0;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getUser(): UserVisitorInterface
    {
        return $this->user;
    }

    public function setUser(UserVisitorInterface $user): static
    {
        $this->user = $user;

        $user->addCosmeticPossessed($this);

        return $this;
    }

    public function getCosmetic(): CosmeticInterface
    {
        return $this->cosmetic;
    }

    public function setCosmetic(CosmeticInterface $cosmetic): static
    {
        $this->cosmetic = $cosmetic;

        return $this;
    }

    public function isSelected(): bool
    {
        return $this->isSelected;
    }

    public function setIsSelected(bool $select): static
    {
        $this->isSelected = $select;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }
}
