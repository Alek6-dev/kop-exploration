<?php

declare(strict_types=1);

namespace App\User\Application\Query\Get;

use App\Shared\Application\Query\QueryInterface;
use App\User\Domain\Model\UserVisitorInterface;

/**
 * @implements QueryInterface<UserVisitorInterface>
 */
final readonly class GetUserVisitorByResetPasswordTokenQuery implements QueryInterface
{
    public function __construct(
        public string $token,
    ) {
    }
}
