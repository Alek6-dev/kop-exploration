<?php

declare(strict_types=1);

namespace App\Parameter\Application\Query\Get;

use App\Parameter\Domain\Exception\ParameterException;
use App\Parameter\Domain\Model\ParameterInterface;
use App\Parameter\Domain\Repository\ParameterRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetParameterQueryHandler
{
    public function __construct(private ParameterRepositoryInterface $repository)
    {
    }

    public function __invoke(GetParameterQuery $query): ParameterInterface
    {
        /** @var ?ParameterInterface $parameter */
        $parameter = $this->repository->withCode($query->code)->first();

        if (!$parameter) {
            throw ParameterException::notFound($query->code);
        }

        return $parameter;
    }
}
