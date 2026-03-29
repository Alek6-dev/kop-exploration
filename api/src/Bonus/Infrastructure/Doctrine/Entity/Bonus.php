<?php

declare(strict_types=1);

namespace App\Bonus\Infrastructure\Doctrine\Entity;

use App\Bonus\Domain\Enum\AttributeEnum;
use App\Bonus\Domain\Enum\BonusTypeEnum;
use App\Bonus\Domain\Enum\OperationEnum;
use App\Bonus\Domain\Enum\SubTargetTypeEnum;
use App\Bonus\Domain\Enum\TargetTypeEnum;
use App\Bonus\Domain\Model\BonusInterface;
use App\Bonus\Infrastructure\Doctrine\Repository\DoctrineBonusRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use function Symfony\Component\Clock\now;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: DoctrineBonusRepository::class)]
class Bonus implements BonusInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::STRING)]
    private ?string $name = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::STRING)]
    private ?string $description = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::STRING)]
    private ?string $example = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::STRING)]
    protected ?string $icon = null;

    #[Vich\UploadableField(mapping: 'bonus', fileNameProperty: 'icon')]
    private ?File $iconFile = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $price = 0;

    #[Assert\Type(type: BonusTypeEnum::class)]
    #[ORM\Column(type: Types::STRING, nullable: true, enumType: BonusTypeEnum::class)]
    private ?BonusTypeEnum $type = null;

    #[Assert\Type(type: TargetTypeEnum::class)]
    #[ORM\Column(type: Types::STRING, enumType: TargetTypeEnum::class)]
    private ?TargetTypeEnum $targetType = null;

    #[Assert\Type(type: SubTargetTypeEnum::class)]
    #[ORM\Column(type: Types::STRING, enumType: SubTargetTypeEnum::class)]
    private ?SubTargetTypeEnum $subTargetType = null;

    #[Assert\Type(type: AttributeEnum::class)]
    #[ORM\Column(type: Types::STRING, nullable: true, enumType: AttributeEnum::class)]
    private ?AttributeEnum $attribute = null;

    #[Assert\Type(type: OperationEnum::class)]
    #[ORM\Column(type: Types::STRING, nullable: true, enumType: OperationEnum::class)]
    private ?OperationEnum $operation = null;

    #[Assert\Positive]
    #[ORM\Column(type: Types::INTEGER, length: 4, nullable: true)]
    private ?int $value = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isJoker = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isEnabled = true;

    #[ORM\Column(type: Types::INTEGER, length: 4)]
    private ?int $sort = null;

    #[Assert\Positive]
    #[ORM\Column(type: Types::INTEGER, length: 1, nullable: true)]
    private ?int $cumulativeTimes = null;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getExample(): ?string
    {
        return $this->example;
    }

    public function setExample(string $example): static
    {
        $this->example = $example;

        return $this;
    }

    public function getIconFile(): ?File
    {
        return $this->iconFile;
    }

    public function setIconFile(?File $iconFile): static
    {
        $this->iconFile = $iconFile;

        if (null !== $iconFile) {
            $this->updatedAt = now();
        }

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): ?BonusTypeEnum
    {
        return $this->type;
    }

    public function setType(?BonusTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getTargetType(): ?TargetTypeEnum
    {
        return $this->targetType;
    }

    public function setTargetType(TargetTypeEnum $targetType): static
    {
        $this->targetType = $targetType;

        return $this;
    }

    public function getSubTargetType(): ?SubTargetTypeEnum
    {
        return $this->subTargetType;
    }

    public function setSubTargetType(SubTargetTypeEnum $subTargetType): static
    {
        $this->subTargetType = $subTargetType;

        return $this;
    }

    public function getAttribute(): ?AttributeEnum
    {
        return $this->attribute;
    }

    public function setAttribute(?AttributeEnum $attribute): static
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getOperation(): ?OperationEnum
    {
        return $this->operation;
    }

    public function setOperation(?OperationEnum $operation): static
    {
        $this->operation = $operation;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function isJoker(): bool
    {
        return $this->isJoker;
    }

    public function setIsJoker(bool $isJoker): static
    {
        $this->isJoker = $isJoker;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): static
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): static
    {
        $this->sort = $sort;

        return $this;
    }

    public function getCumulativeTimes(): ?int
    {
        return $this->cumulativeTimes;
    }

    public function setCumulativeTimes(int $number): static
    {
        $this->cumulativeTimes = $number;

        return $this;
    }
}
