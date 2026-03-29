<?php

declare(strict_types=1);

namespace App\CreditWallet\Application\Query\Get;

use App\CreditWallet\Domain\Exception\CreditPackException;
use App\CreditWallet\Domain\Model\CreditPackInterface;
use App\CreditWallet\Domain\Repository\CreditPackRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetCreditPackQueryHandler
{
    public function __construct(private CreditPackRepositoryInterface $repository)
    {
    }

    public function __invoke(GetCreditPackQuery $query): CreditPackInterface
    {
        /** @var ?CreditPackInterface $model */
        $model = $this->repository->getByUuid($query->uuid);

        if (!$model) {
            throw CreditPackException::notFound($query->uuid);
        }

        return $model;
    }
}
