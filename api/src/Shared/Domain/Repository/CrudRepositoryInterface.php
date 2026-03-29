<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

interface CrudRepositoryInterface
{
    public function add(object $model): void;

    public function update(object $model): void;

    public function remove(object $model): void;

    public function getById(int $id): ?object;

    public function getByUuid(string $uuid): ?object;

    public function withUuid(string $uuid): static;

    /**
     * @param array<string, mixed>  $criteria
     * @param array<string, string> $orderBy
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?object;

    /**
     * @param array<string, mixed>  $criteria
     * @param array<string, string> $orderBy
     *
     * @return array<object>|null
     */
    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array;

    public function first(): ?object;

    /**
     * @return array<object>|null
     */
    public function getResult(): ?array;

    public function getSql(): string;
}
