<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Doctrine\Repository;

use App\Admin\Domain\Repository\UserAdminRepositoryInterface;
use App\Admin\Infrastructure\Doctrine\Entity\UserAdmin;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\UserRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineUserAdminRepository extends DoctrineRepository implements UserAdminRepositoryInterface
{
    use CrudRepositoryTrait;
    use UserRepositoryTrait;
    private const string ENTITY_CLASS = UserAdmin::class;
    private const string ALIAS = 'user_admin';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }
}
