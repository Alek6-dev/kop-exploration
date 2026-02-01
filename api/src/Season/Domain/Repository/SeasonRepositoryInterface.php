<?php

declare(strict_types=1);

namespace App\Season\Domain\Repository;

use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

interface SeasonRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withIsActive(bool $isActive): static;

    public function getLastIsActive(): ?SeasonInterface;

    /**
     * @return array<SeasonInterface>|null
     */
    public function getAll(): ?array;
}
