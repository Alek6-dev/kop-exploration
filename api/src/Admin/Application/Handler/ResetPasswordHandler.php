<?php

declare(strict_types=1);

namespace App\Admin\Application\Handler;

use App\Admin\Domain\Model\UserAdminInterface;
use Symfony\Component\Uid\Uuid;

use function Symfony\Component\Clock\now;

final class ResetPasswordHandler
{
    private const RESET_PASSWORD_TOKEN_VALIDITY = 'PT3H';

    public static function generateResetPasswordToken(UserAdminInterface $user): void
    {
        $user->setResetPasswordToken((string) Uuid::v4());
    }

    public static function isResetPasswordTokenValid(UserAdminInterface $user): bool
    {
        $requestedAt = $user->getResetPasswordRequestedAt();

        if (!$requestedAt) {
            return false;
        }

        return $requestedAt->add(new \DateInterval(self::RESET_PASSWORD_TOKEN_VALIDITY)) > now();
    }

    public static function clearResetPasswordTokenRequest(UserAdminInterface $user): void
    {
        $user->setResetPasswordToken(null);
        $user->setResetPasswordRequestedAt(null);
    }
}
