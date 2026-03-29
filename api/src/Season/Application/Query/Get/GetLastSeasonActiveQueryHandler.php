<?php

declare(strict_types=1);

namespace App\Season\Application\Query\Get;

use App\Season\Domain\Exception\SeasonException;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetLastSeasonActiveQueryHandler
{
    public function __construct(private SeasonRepositoryInterface $repository)
    {
    }

    public function __invoke(GetLastSeasonActiveQuery $query): SeasonInterface
    {
        /** @var ?SeasonInterface $season */
        $season = $this->repository->getLastIsActive();

        if (!$season) {
            throw SeasonException::notActiveSeason();
        }

        return $season;
    }
}
