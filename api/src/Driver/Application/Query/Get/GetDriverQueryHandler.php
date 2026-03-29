<?php

declare(strict_types=1);

namespace App\Driver\Application\Query\Get;

use App\Driver\Domain\Exception\DriverException;
use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Domain\Repository\DriverRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetDriverQueryHandler
{
    public function __construct(private DriverRepositoryInterface $repository)
    {
    }

    public function __invoke(GetDriverQuery $query): DriverInterface
    {
        /** @var ?DriverInterface $model */
        $model = $this->repository->getByUuid($query->uuid);

        if (!$model) {
            throw DriverException::notFound($query->uuid);
        }

        return $model;
    }
}
