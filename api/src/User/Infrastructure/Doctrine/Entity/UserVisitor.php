<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Entity;

use App\Cosmetic\Domain\Enum\TypeCosmeticEnum;
use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Cosmetic\Domain\Model\CosmeticPossessedInterface;
use App\Cosmetic\Infrastructure\Doctrine\Entity\CosmeticPossessed;
use App\CreditWallet\Domain\Model\CreditWalletInterface;
use App\CreditWallet\Infrastructure\Doctrine\Entity\CreditWallet;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use App\Shared\Domain\Enum\User\StatusEnum;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\HasImageTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UserTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Infrastructure\Doctrine\Repository\DoctrineUserVisitorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: DoctrineUserVisitorRepository::class)]
#[UniqueEntity(
    fields: 'email',
    message: 'This email is already in use.',
    errorPath: 'email',
)]
#[UniqueEntity(
    fields: 'pseudo',
    message: 'This pseudo is already in use.',
    errorPath: 'pseudo',
)]
#[ORM\UniqueConstraint(
    name: 'visitor_pseudo_idx',
    columns: ['pseudo']
)]
#[ORM\UniqueConstraint(
    name: 'visitor_email_idx',
    columns: ['email']
)]
class UserVisitor implements UserVisitorInterface, \Serializable
{
    use HasImageTrait;
    use IdableTrait;
    use TimestampableTrait;
    use UserTrait;
    use UuidableTrait;

    public const string EMAIL_UNIQUE_IDX = 'visitor_email_idx';
    public const string PSEUDO_UNIQUE_IDX = 'visitor_pseudo_idx';

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $image = null;

    #[Vich\UploadableField(mapping: 'avatar', fileNameProperty: 'image')]
    protected ?File $imageFile = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $emailValidationToken = null;

    #[Assert\Length(min: 3, max: 25)]
    #[ORM\Column(type: Types::STRING, unique: true)]
    private ?string $pseudo = null;

    /**
     * @var Collection<int, PlayerInterface>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Player::class)]
    private Collection $players;

    /**
     * @var Collection<int, CosmeticPossessedInterface>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CosmeticPossessed::class, cascade: ['persist', 'remove'])]
    private Collection $cosmeticsPossessed;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: CreditWallet::class, cascade: ['persist', 'remove'])]
    private ?CreditWalletInterface $creditWallet = null;

    public function __construct()
    {
        $this->status = StatusEnum::WAITING_ADMIN_CONFIRMATION;
        $this->players = new ArrayCollection();
        $this->cosmeticsPossessed = new ArrayCollection();
        $this->roles[] = 'ROLE_USER';
        $this->uuid = (string) new UuidV4();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->emailValidationToken = (string) Uuid::v4();
    }

    public function confirm(): static
    {
        $this->status = StatusEnum::EMAIL_TO_VALIDATE;

        return $this;
    }

    public function validate(): static
    {
        $this->status = StatusEnum::CREATED;
        $this->emailValidationToken = null;

        return $this;
    }

    public function delete(): static
    {
        $this->status = StatusEnum::DELETED;

        return $this;
    }

    public function getEmailValidationToken(): ?string
    {
        return $this->emailValidationToken;
    }

    public function setEmailValidationToken(?string $emailValidationToken): static
    {
        $this->emailValidationToken = $emailValidationToken;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->pseudo;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->email,
            $this->pseudo,
            $this->password,
            $this->image,
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->id,
            $this->email,
            $this->pseudo,
            $this->password,
            $this->image,
        ] = unserialize($data);
    }

    /**
     * @return Collection<int, PlayerInterface>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    /**
     * @return Collection<int, CosmeticPossessedInterface>
     */
    public function getCosmeticsPossessed(): Collection
    {
        return $this->cosmeticsPossessed;
    }

    /**
     * @return Collection<int, CosmeticInterface>
     */
    public function getCosmetics(): Collection
    {
        return $this->cosmeticsPossessed->map(function (CosmeticPossessedInterface $cosmeticPossessed) {
            return $cosmeticPossessed->getCosmetic();
        });
    }

    /**
     * @return Collection<int, CosmeticInterface>
     */
    public function getSelectedCosmetics(): Collection
    {
        return $this->cosmeticsPossessed->map(function (CosmeticPossessedInterface $cosmeticPossessed) {
            if (!$cosmeticPossessed->isSelected()) {
                return null;
            }

            return $cosmeticPossessed->getCosmetic();
        });
    }

    public function getCarCosmetic(): ?CosmeticInterface
    {
        return $this->cosmeticsPossessed->findFirst(function (int $key, CosmeticPossessedInterface $cosmeticPossessed): bool {
            if (!$cosmeticPossessed->isSelected() || TypeCosmeticEnum::CAR !== $cosmeticPossessed->getCosmetic()?->getType()) {
                return false;
            }

            return true;
        })?->getCosmetic();
    }

    public function getHelmetCosmetic(): ?CosmeticInterface
    {
        return $this->cosmeticsPossessed->findFirst(function (int $key, CosmeticPossessedInterface $cosmeticPossessed): bool {
            if (!$cosmeticPossessed->isSelected() || TypeCosmeticEnum::HELMET !== $cosmeticPossessed->getCosmetic()?->getType()) {
                return false;
            }

            return true;
        })?->getCosmetic();
    }

    public function getSuitCosmetic(): ?CosmeticInterface
    {
        return $this->cosmeticsPossessed->findFirst(function (int $key, CosmeticPossessedInterface $cosmeticPossessed): bool {
            if (!$cosmeticPossessed->isSelected() || TypeCosmeticEnum::SUIT !== $cosmeticPossessed->getCosmetic()?->getType()) {
                return false;
            }

            return true;
        })?->getCosmetic();
    }

    public function getCreditWallet(): ?CreditWalletInterface
    {
        return $this->creditWallet;
    }

    public function setCreditWallet(CreditWalletInterface $creditWallet): static
    {
        $creditWallet->setUser($this);

        $this->creditWallet = $creditWallet;

        return $this;
    }

    public function getRelativeImagePath(): ?string
    {
        return 'uploads/images/avatar/'.$this->image;
    }

    public function addCosmeticPossessed(CosmeticPossessedInterface $cosmeticPossessed): static
    {
        if (!$cosmeticPossessed->getUser()) {
            $cosmeticPossessed->setUser($this);
        }

        $this->cosmeticsPossessed[] = $cosmeticPossessed;

        return $this;
    }

    public function removeCosmeticPossessed(CosmeticPossessedInterface $cosmeticPossessed): void
    {
        $this->cosmeticsPossessed->removeElement($cosmeticPossessed);
    }
}
