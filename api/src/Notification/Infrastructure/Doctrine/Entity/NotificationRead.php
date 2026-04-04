<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Doctrine\Entity;

use App\Notification\Infrastructure\Doctrine\Repository\DoctrineNotificationReadRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: DoctrineNotificationReadRepository::class)]
class NotificationRead
{
    use IdableTrait;
    use UuidableTrait;

    #[ORM\ManyToOne(targetEntity: UserVisitor::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private UserVisitor $user;

    #[ORM\ManyToOne(targetEntity: Notification::class, inversedBy: 'reads')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Notification $notification;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $readAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getUser(): UserVisitor
    {
        return $this->user;
    }

    public function setUser(UserVisitor $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }

    public function setNotification(Notification $notification): static
    {
        $this->notification = $notification;

        return $this;
    }

    public function getReadAt(): ?\DateTimeImmutable
    {
        return $this->readAt;
    }

    public function setReadAt(?\DateTimeImmutable $readAt): static
    {
        $this->readAt = $readAt;

        return $this;
    }

    public function isRead(): bool
    {
        return null !== $this->readAt;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isDeleted(): bool
    {
        return null !== $this->deletedAt;
    }
}
