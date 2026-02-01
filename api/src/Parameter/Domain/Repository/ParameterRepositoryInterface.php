<?php

declare(strict_types=1);

namespace App\Parameter\Domain\Repository;

use App\Parameter\Domain\Model\ParameterInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

interface ParameterRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withCode(string $code): static;

    public function getParameterByCode(string $code): ?ParameterInterface;

    /**
     * @return array<ParameterInterface>|null
     */
    public function getAll(): ?array;
}
