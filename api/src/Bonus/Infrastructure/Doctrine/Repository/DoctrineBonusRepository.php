<?php

declare(strict_types=1);

namespace App\Bonus\Infrastructure\Doctrine\Repository;

use App\Bonus\Domain\Enum\AttributeEnum;
use App\Bonus\Domain\Enum\BonusTypeEnum;
use App\Bonus\Domain\Enum\OperationEnum;
use App\Bonus\Domain\Enum\SubTargetTypeEnum;
use App\Bonus\Domain\Enum\TargetTypeEnum;
use App\Bonus\Domain\Repository\BonusRepositoryInterface;
use App\Bonus\Infrastructure\Doctrine\Entity\Bonus;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineBonusRepository extends DoctrineRepository implements BonusRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = Bonus::class;
    private const string ALIAS = 'bonus';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function withIsEnabled(bool $isEnabled = true): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($isEnabled): void {
            $qb->andWhere(sprintf('%s.isEnabled = :isEnabled', self::ALIAS))
                ->setParameter('isEnabled', $isEnabled)
            ;
        });
    }

    public function withType(BonusTypeEnum $type): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($type): void {
            $qb->andWhere(sprintf('%s.type = :type OR %s.type IS NULL', self::ALIAS, self::ALIAS))
                ->setParameter('type', $type)
            ;
        });
    }

    public function withTargetType(TargetTypeEnum $targetType): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($targetType): void {
            $qb->andWhere(sprintf('%s.targetType = :targetType', self::ALIAS))->setParameter('targetType', $targetType);
        });
    }

    public function withSubTargetType(SubTargetTypeEnum $subTargetType): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($subTargetType): void {
            $qb->andWhere(sprintf('%s.subTargetType = :subTargetType', self::ALIAS))->setParameter('subTargetType', $subTargetType);
        });
    }

    public function withAttribute(AttributeEnum $attribute): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($attribute): void {
            $qb->andWhere(sprintf('%s.attribute = :attribute', self::ALIAS))->setParameter('attribute', $attribute);
        });
    }

    public function withOperation(OperationEnum $operation): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($operation): void {
            $qb->andWhere(sprintf('%s.operation = :operation', self::ALIAS))->setParameter('operation', $operation);
        });
    }

    public function withIsJoker(bool $isJoker): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($isJoker): void {
            $qb->andWhere(sprintf('%s.isJoker = :isJoker', self::ALIAS))->setParameter('isJoker', $isJoker);
        });
    }

    public function withOrderByPrice(string $direction = 'DESC'): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($direction): void {
            $qb->addOrderBy(sprintf('%s.price', self::ALIAS), $direction);
        });
    }

    public function withOrderBySort(string $direction = 'DESC'): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($direction): void {
            $qb->addOrderBy(sprintf('%s.sort', self::ALIAS), $direction);
        });
    }
}
