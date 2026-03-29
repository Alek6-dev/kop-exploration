<?php

declare(strict_types=1);

namespace App\Parameter\Infrastructure\Doctrine\Entity;

use App\Parameter\Domain\Enum\TypeEnum;
use App\Parameter\Domain\Model\ParameterInterface;
use App\Parameter\Infrastructure\Doctrine\Repository\DoctrineParameterRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineParameterRepository::class)]
class Parameter implements ParameterInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $label = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private ?string $code = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string|int|bool|null $value = null;

    #[ORM\Column(type: Types::STRING, length: 255, enumType: TypeEnum::class)]
    private ?TypeEnum $type = null;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getValue(): string|int|bool|null
    {
        return $this->value;
    }

    public function setValue(string|int|bool|null $value): static
    {
        $this->value = (string) $value;

        return $this;
    }

    public function getType(): ?TypeEnum
    {
        return $this->type;
    }

    public function setType(TypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }
}
