<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\Doctrine\Repository;

use App\CreditWallet\Domain\Repository\CreditPackRepositoryInterface;
use App\CreditWallet\Infrastructure\Doctrine\Entity\CreditPack;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineCreditPackRepository extends DoctrineRepository implements CreditPackRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = CreditPack::class;
    private const string ALIAS = 'credit_pack';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function withOrderByCredit(string $direction = 'DESC'): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($direction): void {
            $qb->addOrderBy(sprintf('%s.credit', self::ALIAS), $direction);
        });
    }
}
