<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\Doctrine\Repository;

use App\CreditWallet\Infrastructure\Doctrine\Entity\AdminCreditGrant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdminCreditGrant>
 */
class DoctrineAdminCreditGrantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminCreditGrant::class);
    }
}
