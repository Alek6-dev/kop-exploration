<?php

declare(strict_types=1);

namespace App\Cosmetic\Application\Query\Get;

use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Cosmetic\Domain\Repository\CosmeticRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetCosmeticQueryHandler
{
    public function __construct(private CosmeticRepositoryInterface $repository)
    {
    }

    public function __invoke(GetCosmeticQuery $query): CosmeticInterface
    {
        /** @var ?CosmeticInterface $cosmetic */
        $cosmetic = $this->repository->getByUuid($query->uuid);

        if (!$cosmetic) {
            throw new \Exception($query->uuid);
        }

        return $cosmetic;
    }
}
