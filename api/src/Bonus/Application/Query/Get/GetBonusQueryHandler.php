<?php

declare(strict_types=1);

namespace App\Bonus\Application\Query\Get;

use App\Bonus\Domain\Exception\BonusException;
use App\Bonus\Domain\Model\BonusInterface;
use App\Bonus\Domain\Repository\BonusRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetBonusQueryHandler
{
    public function __construct(private BonusRepositoryInterface $repository)
    {
    }

    public function __invoke(GetBonusQuery $query): BonusInterface
    {
        /** @var ?BonusInterface $model */
        $model = $this->repository
            ->withIsEnabled()
            ->getByUuid($query->uuid)
        ;

        if (null === $model) {
            throw BonusException::notFound($query->uuid);
        }

        return $model;
    }
}
