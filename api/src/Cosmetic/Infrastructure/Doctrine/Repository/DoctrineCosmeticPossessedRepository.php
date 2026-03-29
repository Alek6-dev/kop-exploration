<?php

declare(strict_types=1);

namespace App\Cosmetic\Infrastructure\Doctrine\Repository;

use App\Cosmetic\Domain\Enum\TypeCosmeticEnum;
use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Cosmetic\Domain\Repository\CosmeticPossessedRepositoryInterface;
use App\Cosmetic\Infrastructure\Doctrine\Entity\CosmeticPossessed;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineCosmeticPossessedRepository extends DoctrineRepository implements CosmeticPossessedRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = CosmeticPossessed::class;
    private const string ALIAS = 'cosmetic_possessed';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function withCosmetic(CosmeticInterface $cosmetic): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($cosmetic): void {
            $qb->andWhere(sprintf('%s.cosmetic = :cosmetic', self::ALIAS))
                ->setParameter('cosmetic', $cosmetic)
            ;
        });
    }

    public function withUser(UserVisitorInterface $user): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($user): void {
            $qb->andWhere(sprintf('%s.user = :user', self::ALIAS))
                ->setParameter('user', $user)
            ;
        });
    }

    public function withIsSelected(bool $select): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($select): void {
            $qb->andWhere(sprintf('%s.isSelected = :select', self::ALIAS))
                ->setParameter('select', $select)
            ;
        });
    }

    public function withTypeCosmetic(TypeCosmeticEnum $type): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($type): void {
            $qb->leftJoin(sprintf('%s.cosmetic', self::ALIAS), DoctrineCosmeticRepository::getAlias())
                ->andWhere(sprintf('%s.type = :type', DoctrineCosmeticRepository::getAlias()))
                ->setParameter('type', $type)
            ;
        });
    }
}
