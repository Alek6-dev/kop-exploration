<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Notification\Application\Command\MarkNotificationRead\MarkNotificationReadCommand;
use App\Notification\Infrastructure\ApiPlatform\Resource\NotificationResource;
use App\Shared\Application\Command\CommandBusInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<NotificationResource, void>
 */
final readonly class MarkNotificationReadProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        /** @var NotificationResource $data */
        /** @var UserVisitor $user */
        $user = $this->security->getUser();

        $this->commandBus->dispatch(new MarkNotificationReadCommand(
            notificationUuid: $data->uuid,
            user: $user,
        ));
    }
}
