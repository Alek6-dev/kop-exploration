<?php

declare(strict_types=1);

namespace App\User\Application\Query\Stat\CountCosmeticsPossessed;

use App\Cosmetic\Domain\Repository\CosmeticPossessedRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;

#[AsQueryHandler]
final readonly class CountCosmeticsPossessedQueryHandler
{
    public function __construct(
        private UserVisitorRepositoryInterface $userVisitorRepository,
        private CosmeticPossessedRepositoryInterface $repository
    ) {
    }

    public function __invoke(CountCosmeticsPossessedQuery $query): int
    {
        /** @var ?UserVisitorInterface $user */
        $user = $this->userVisitorRepository->getByUuid($query->userUuid);

        if (!$user) {
            UserVisitorException::notFound($query->userUuid);
        }

        return $this->repository
            ->withUser($user)
            ->count()
        ;
    }
}
