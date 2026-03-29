<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Repository\Trait;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Uid\UuidV4;

trait CrudRepositoryTrait
{
    public function add(object $model): void
    {
        $this->em->persist($model);
    }

    public function update(object $model): void
    {
        $this->em->persist($model);
    }

    public function remove(object $model): void
    {
        $this->em->remove($model);
    }

    public function getById(int $id): ?object
    {
        return $this->em->find(self::ENTITY_CLASS, $id);
    }

    public function getByUuid(string $uuid): ?object
    {
        try {
            return $this
                ->withUuid($uuid)
                ->first();
        } catch (\InvalidArgumentException) {
            return null;
        }
    }

    public function withUuid(string $uuid): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($uuid): void {
            $qb
                ->andWhere(sprintf('%s.uuid = :uuid', self::ALIAS))
                ->setParameter(':uuid', UuidV4::fromString($uuid)->toBinary())
            ;
        });
    }

    /**
     * @param array<string, mixed>  $criteria
     * @param array<string, string> $orderBy
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?object
    {
        $persister = $this->em->getUnitOfWork()->getEntityPersister($this::ENTITY_CLASS);

        return $persister->load($criteria, null, null, [], null, 1, $orderBy);
    }

    /**
     * @param array<string, mixed>  $criteria
     * @param array<string, string> $orderBy
     *
     * @return array<object>|null
     */
    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        $persister = $this->em->getUnitOfWork()->getEntityPersister($this::ENTITY_CLASS);

        return $persister->loadAll($criteria, $orderBy, $limit, $offset);
    }

    public function first(): ?object
    {
        return $this->query()->setMaxResults(1)->getQuery()->getOneOrNullResult();
    }

    /**
     * @return array<object>|null
     */
    public function getResult(): ?array
    {
        return $this->query()->getQuery()->getResult();
    }

    public function getSql(): string
    {
        return $this->query()->getQuery()->getSQL();
    }

    public static function getAlias(): string
    {
        return self::ALIAS;
    }
}
