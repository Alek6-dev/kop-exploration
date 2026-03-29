<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\Doctrine\Repository;

use App\CreditWallet\Domain\Repository\CreditWalletRepositoryInterface;
use App\CreditWallet\Infrastructure\Doctrine\Entity\CreditWallet;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineCreditWalletRepository extends DoctrineRepository implements CreditWalletRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = CreditWallet::class;
    private const string ALIAS = 'credit_wallet';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }
}
