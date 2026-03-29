<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Admin\Infrastructure\Doctrine\Entity\UserAdmin;
use App\Shared\Domain\Enum\User\RoleEnum;
use App\Shared\Domain\Enum\User\StatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserAdminFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new UserAdmin();
        $admin->setFirstName('Admin');
        $admin->setLastName('Super');
        $admin->setEmail('admin+super@kop.fr');
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'password'));
        $admin->setRoles([RoleEnum::ROLE_SUPER_ADMIN->value]);
        $admin->setStatus(StatusEnum::CREATED);

        $manager->persist($admin);

        $manager->flush();
    }
}
