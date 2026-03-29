<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Entity\Traits;

use App\Shared\Domain\Enum\User\StatusEnum;
use App\Shared\Domain\Model\Behaviors\UserInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\Clock\now;

trait UserTrait
{
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    protected ?string $firstName = null;
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    protected ?string $lastName = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[ORM\Column(length: 180, unique: true)]
    protected string $email;

    /**
     * @var array<int, string>
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    protected array $roles = [];

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    protected ?string $password = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    protected ?string $resetPasswordToken = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?\DateTimeImmutable $resetPasswordRequestedAt = null;

    #[Assert\Type(type: StatusEnum::class)]
    #[ORM\Column(type: Types::STRING, length: 255, enumType: StatusEnum::class)]
    protected ?StatusEnum $status = null;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getMainRole(): ?string
    {
        return reset($this->roles);
    }

    public function setMainRole(string $role): static
    {
        $this->setRoles([$role]);

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        $this->setResetPasswordToken(null);
        $this->setResetPasswordRequestedAt(null);

        return $this;
    }

    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?string $resetPasswordToken): static
    {
        $this->resetPasswordToken = $resetPasswordToken;

        $this->setResetPasswordRequestedAt(now());

        return $this;
    }

    public function getResetPasswordRequestedAt(): ?\DateTimeImmutable
    {
        return $this->resetPasswordRequestedAt;
    }

    public function setResetPasswordRequestedAt(?\DateTimeImmutable $resetPasswordRequestedAt): static
    {
        $this->resetPasswordRequestedAt = $resetPasswordRequestedAt;

        return $this;
    }

    public function getStatus(): StatusEnum
    {
        return $this->status;
    }

    public function setStatus(StatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }
}
