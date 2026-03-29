<?php

declare(strict_types=1);

namespace App\Duel\Application\Query\GetByUuid;

use App\Duel\Domain\Exception\DuelException;
use App\Duel\Domain\Model\DuelInterface;
use App\Duel\Domain\Repository\DuelRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetDuelByUuidQueryHandler
{
    public function __construct(private DuelRepositoryInterface $repository)
    {
    }

    public function __invoke(GetDuelByUuidQuery $query): DuelInterface
    {
        /** @var ?DuelInterface $duel */
        $duel = $this->repository
            ->getByUuid($query->uuid)
        ;

        if (!$duel) {
            throw DuelException::notFound($query->uuid);
        }

        return $duel;
    }
}
