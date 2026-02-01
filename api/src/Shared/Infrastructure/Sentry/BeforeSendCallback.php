<?php

namespace App\Shared\Infrastructure\Sentry;

use Sentry\Event;
use Sentry\EventHint;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BeforeSendCallback
{
    public function getBeforeSend(): callable
    {
        return static function (Event $event, ?EventHint $hint): ?Event {
            $ignored_exceptions = [
                NotFoundHttpException::class,
                AccessDeniedException::class,
            ];

            if (null === $hint) {
                return $event;
            }

            foreach ($ignored_exceptions as $ignored_exception) {
                if ($hint->exception instanceof $ignored_exception) {
                    return null;
                }
            }

            return $event;
        };
    }
}
