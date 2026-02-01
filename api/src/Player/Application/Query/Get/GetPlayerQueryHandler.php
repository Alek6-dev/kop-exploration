<?php

declare(strict_types=1);

namespace App\Player\Application\Query\Get;

use App\Player\Domain\Exception\PlayerException;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetPlayerQueryHandler
{
    public function __construct(private PlayerRepositoryInterface $repository)
    {
    }

    public function __invoke(GetPlayerQuery $query): PlayerInterface
    {
        /** @var ?PlayerInterface $model */
        $model = $this->repository->getByUuid($query->uuid);

        if (!$model) {
            throw PlayerException::notFound($query->uuid);
        }

        return $model;
    }
}
