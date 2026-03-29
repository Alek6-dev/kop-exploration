<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\Doctrine\Repository;

use App\CreditWallet\Domain\Enum\TransactionType;
use App\CreditWallet\Domain\Repository\CreditWalletTransactionRepositoryInterface;
use App\CreditWallet\Infrastructure\Doctrine\Entity\CreditWalletTransaction;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineCreditWalletTransactionRepository extends DoctrineRepository implements CreditWalletTransactionRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = CreditWalletTransaction::class;
    private const string ALIAS = 'credit_wallet_transaction';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function withType(TransactionType $type): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($type): void {
            $qb->andWhere(sprintf('%s.type IN (:type)', self::ALIAS))
                ->setParameter('type', $type);
        });
    }
}
