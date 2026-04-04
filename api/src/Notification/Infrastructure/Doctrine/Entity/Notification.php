<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Doctrine\Entity;

use App\Notification\Domain\Enum\NotificationTypeEnum;
use App\Notification\Infrastructure\Doctrine\Repository\DoctrineNotificationRepository;
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

#[ORM\Entity(repositoryClass: DoctrineNotificationRepository::class)]
class Notification
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: Types::STRING)]
    private string $title = '';

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    private string $body = '';

    #[Assert\NotNull]
    #[ORM\Column(type: Types::STRING, enumType: NotificationTypeEnum::class)]
    private NotificationTypeEnum $type = NotificationTypeEnum::EDITORIAL;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $isForAll = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $showAsPopup = false;

    /** @var Collection<int, UserVisitor> */
    #[ORM\ManyToMany(targetEntity: UserVisitor::class)]
    #[ORM\JoinTable(name: 'notification_targets')]
    private Collection $targets;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $scheduledAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $expiresAt = null;

    /** @var Collection<int, NotificationRead> */
    #[ORM\OneToMany(targetEntity: NotificationRead::class, mappedBy: 'notification', cascade: ['remove'])]
    private Collection $reads;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
        $this->targets = new ArrayCollection();
        $this->reads = new ArrayCollection();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getType(): NotificationTypeEnum
    {
        return $this->type;
    }

    public function setType(NotificationTypeEnum|string $type): static
    {
        $this->type = $type instanceof NotificationTypeEnum ? $type : NotificationTypeEnum::from($type);

        return $this;
    }

    public function isForAll(): bool
    {
        return $this->isForAll;
    }

    public function setIsForAll(bool $isForAll): static
    {
        $this->isForAll = $isForAll;

        return $this;
    }

    public function isShowAsPopup(): bool
    {
        return $this->showAsPopup;
    }

    public function setShowAsPopup(bool $showAsPopup): static
    {
        $this->showAsPopup = $showAsPopup;

        return $this;
    }

    /** @return Collection<int, UserVisitor> */
    public function getTargets(): Collection
    {
        return $this->targets;
    }

    public function addTarget(UserVisitor $user): static
    {
        if (!$this->targets->contains($user)) {
            $this->targets->add($user);
        }

        return $this;
    }

    public function removeTarget(UserVisitor $user): static
    {
        $this->targets->removeElement($user);

        return $this;
    }

    public function getScheduledAt(): ?\DateTimeImmutable
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(?\DateTimeImmutable $scheduledAt): static
    {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /** @return Collection<int, NotificationRead> */
    public function getReads(): Collection
    {
        return $this->reads;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
