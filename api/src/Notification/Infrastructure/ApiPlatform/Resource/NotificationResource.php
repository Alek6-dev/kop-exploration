<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Notification\Domain\Enum\NotificationTypeEnum;
use App\Notification\Infrastructure\ApiPlatform\State\Processor\DeleteNotificationProcessor;
use App\Notification\Infrastructure\ApiPlatform\State\Processor\MarkAllNotificationsReadProcessor;
use App\Notification\Infrastructure\ApiPlatform\State\Processor\MarkNotificationReadProcessor;
use App\Notification\Infrastructure\ApiPlatform\State\Provider\NotificationCollectionProvider;
use App\Notification\Infrastructure\ApiPlatform\State\Provider\NotificationItemProvider;
use App\Notification\Infrastructure\ApiPlatform\State\Provider\NotificationPopupProvider;
use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ApiResource(
    shortName: 'Notification',
    operations: [
        new GetCollection(
            uriTemplate: '/notifications/popup',
            paginationEnabled: false,
            provider: NotificationPopupProvider::class,
        ),
        new GetCollection(
            paginationEnabled: false,
            provider: NotificationCollectionProvider::class,
        ),
        new Post(
            uriTemplate: '/notifications/{uuid}/read',
            input: false,
            output: false,
            provider: NotificationItemProvider::class,
            processor: MarkNotificationReadProcessor::class,
        ),
        new Post(
            uriTemplate: '/notifications/read-all',
            input: false,
            output: false,
            processor: MarkAllNotificationsReadProcessor::class,
        ),
        new Delete(
            provider: NotificationItemProvider::class,
            processor: DeleteNotificationProcessor::class,
        ),
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
    normalizationContext: ['skip_null_values' => false, DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'],
)]
class NotificationResource
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        public ?string $title = null,
        public ?string $body = null,
        public ?NotificationTypeEnum $type = null,
        public ?bool $isRead = null,
        public ?bool $showAsPopup = null,
        public ?\DateTimeImmutable $publishedAt = null,
    ) {
    }

    public static function fromEntity(Notification $entity, bool $isRead): self
    {
        return new self(
            uuid: $entity->getUuid(),
            title: $entity->getTitle(),
            body: $entity->getBody(),
            type: $entity->getType(),
            isRead: $isRead,
            showAsPopup: $entity->isShowAsPopup(),
            publishedAt: $entity->getPublishedAt(),
        );
    }
}
