<?php

declare(strict_types=1);

namespace App\Cosmetic\Infrastructure\Doctrine\Entity;

use App\Cosmetic\Domain\Enum\TypeCosmeticEnum;
use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Cosmetic\Domain\Model\CosmeticPossessedInterface;
use App\Cosmetic\Infrastructure\Doctrine\Repository\DoctrineCosmeticRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

use function Symfony\Component\Clock\now;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: DoctrineCosmeticRepository::class)]
class Cosmetic implements CosmeticInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING)]
    private ?string $name;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING)]
    private ?string $description;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $price;

    #[Assert\Type(type: TypeCosmeticEnum::class)]
    #[ORM\Column(type: Types::INTEGER, length: 2, enumType: TypeCosmeticEnum::class)]
    private ?TypeCosmeticEnum $type;

    #[Assert\CssColor(formats: 'hex_long')]
    #[ORM\Column(type: Types::STRING)]
    private ?string $color;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::STRING)]
    protected ?string $image1 = null;

    #[Vich\UploadableField(mapping: 'cosmetic', fileNameProperty: 'image1')]
    private ?File $image1File = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::STRING)]
    protected ?string $image2 = null;

    #[Vich\UploadableField(mapping: 'cosmetic', fileNameProperty: 'image2')]
    private ?File $image2File = null;

    #[ORM\OneToMany(mappedBy: 'cosmetic', targetEntity: CosmeticPossessed::class)]
    private ?Collection $possessedByUsers;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isDefault = false;

    public function __construct()
    {
        $this->possessedByUsers = new ArrayCollection();
        $this->uuid = (string) new UuidV4();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): ?TypeCosmeticEnum
    {
        return $this->type;
    }

    public function setType(?TypeCosmeticEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getImage1File(): ?File
    {
        return $this->image1File;
    }

    public function setImage1File(?File $image1File): static
    {
        $this->image1File = $image1File;

        if (null !== $image1File) {
            $this->updatedAt = now();
        }

        return $this;
    }

    public function getImage1(): ?string
    {
        return $this->image1;
    }

    public function setImage1(?string $image1): static
    {
        $this->image1 = $image1;

        return $this;
    }

    public function getImage2File(): ?File
    {
        return $this->image2File;
    }

    public function setImage2File(?File $image2File): static
    {
        $this->image2File = $image2File;

        if (null !== $image2File) {
            $this->updatedAt = now();
        }

        return $this;
    }

    public function getImage2(): ?string
    {
        return $this->image2;
    }

    public function setImage2(?string $image2): static
    {
        $this->image2 = $image2;

        return $this;
    }

    public function isPossessedByUser(UserVisitorInterface $user): bool
    {
        return $this->possessedByUsers->exists(fn (int $key, CosmeticPossessedInterface $cosmeticPossessed) => $cosmeticPossessed->getUser() === $user);
    }

    public function isSelectedByUser(UserVisitorInterface $user): bool
    {
        return $this->possessedByUsers->exists(fn (int $key, CosmeticPossessedInterface $cosmeticPossessed) => $cosmeticPossessed->getUser() === $user && $cosmeticPossessed->isSelected());
    }

    public function getRelativeImage1Path(): ?string
    {
        return 'uploads/images/cosmetic/'.$this->image1;
    }

    public function getRelativeImage2Path(): ?string
    {
        return 'uploads/images/cosmetic/'.$this->image2;
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): static
    {
        $this->isDefault = $isDefault;

        return $this;
    }
}
