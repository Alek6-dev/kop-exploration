<?php

declare(strict_types=1);

namespace App\Season\Infrastructure\Doctrine\Repository;

use App\Season\Domain\Model\SeasonInterface;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Season\Infrastructure\Doctrine\Entity\Season;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineSeasonRepository extends DoctrineRepository implements SeasonRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = Season::class;
    private const string ALIAS = 'season';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function withIsActive(bool $isActive): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($isActive): void {
            $qb->where(sprintf('%s.isActive = :isActive', self::ALIAS))->setParameter('isActive', $isActive);
        });
    }

    public function getLastIsActive(): ?SeasonInterface
    {
        return $this->withIsActive(true)->query()
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getAll(): ?array
    {
        return $this->query()->getQuery()->getResult();
    }
}
