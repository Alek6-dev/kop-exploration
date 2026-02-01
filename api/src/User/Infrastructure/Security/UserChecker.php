<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Security;

use App\Shared\Domain\Enum\User\StatusEnum;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof UserVisitorInterface) {
            return;
        }

        if (StatusEnum::CREATED !== $user->getStatus()) {
            throw new DisabledException();
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
