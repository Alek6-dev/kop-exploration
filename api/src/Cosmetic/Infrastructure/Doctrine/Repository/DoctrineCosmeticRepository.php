<?php

declare(strict_types=1);

namespace App\Cosmetic\Infrastructure\Doctrine\Repository;

use App\Cosmetic\Domain\Enum\TypeCosmeticEnum;
use App\Cosmetic\Domain\Repository\CosmeticRepositoryInterface;
use App\Cosmetic\Infrastructure\Doctrine\Entity\Cosmetic;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

class DoctrineCosmeticRepository extends DoctrineRepository implements CosmeticRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = Cosmetic::class;
    private const string ALIAS = 'cosmetic';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function withName(string $name): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($name): void {
            $qb->andWhere(sprintf('%s.name = :name', self::ALIAS))->setParameter('name', $name);
        });
    }

    public function withType(TypeCosmeticEnum $type): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($type): void {
            $qb->andWhere(sprintf('%s.type = :type', self::ALIAS))->setParameter('type', $type);
        });
    }

    public function withOrderByPossessed(UserVisitorInterface $user): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($user): void {
            $qb->leftJoin(sprintf('%s.possessedByUsers', self::ALIAS), DoctrineCosmeticPossessedRepository::getAlias(), Join::WITH, sprintf('%s.user = :user', DoctrineCosmeticPossessedRepository::getAlias()))
                ->orderBy(sprintf('%s.isSelected', DoctrineCosmeticPossessedRepository::getAlias()), 'DESC')
                ->setParameter(':user', $user)
            ;
        });
    }

    public function withIsDefault(bool $isDefault = false): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($isDefault): void {
            $qb->andWhere(sprintf('%s.isDefault = :isDefault', self::ALIAS))
                ->setParameter('isDefault', $isDefault);
        });
    }
}
