<?php

declare(strict_types=1);

namespace App\Race\Application\Query\Get;

use App\Race\Domain\Exception\RaceException;
use App\Race\Domain\Model\RaceInterface;
use App\Race\Domain\Repository\RaceRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetRaceQueryHandler
{
    public function __construct(private RaceRepositoryInterface $repository)
    {
    }

    public function __invoke(GetRaceQuery $query): RaceInterface
    {
        /** @var ?RaceInterface $race */
        $race = $this->repository
            ->getByUuid($query->uuid)
        ;

        if (!$race) {
            throw RaceException::notFound($query->uuid);
        }

        return $race;
    }
}
