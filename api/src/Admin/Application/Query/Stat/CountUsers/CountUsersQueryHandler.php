<?php

declare(strict_types=1);

namespace App\Admin\Application\Query\Stat\CountUsers;

use App\Shared\Application\Query\AsQueryHandler;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;

#[AsQueryHandler]
final readonly class CountUsersQueryHandler
{
    public function __construct(
        private UserVisitorRepositoryInterface $userVisitorRepository,
    ) {
    }

    public function __invoke(CountUsersQuery $query): int
    {
        return $this->userVisitorRepository->withStatuses($query->statuses)->count();
    }
}
