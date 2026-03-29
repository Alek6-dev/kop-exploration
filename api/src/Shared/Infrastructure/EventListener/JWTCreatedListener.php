<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventListener;

use App\User\Domain\Model\UserVisitorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();
        if (!$user instanceof UserVisitorInterface) {
            return;
        }
        $payload = $event->getData();
        $payload['pseudo'] = $user->getPseudo();
        $payload['email'] = $user->getEmail();
        $payload['id'] = $user->getUuid();
        $payload['avatar_url'] = $user->getRelativeImagePath();

        $event->setData($payload);
    }
}
