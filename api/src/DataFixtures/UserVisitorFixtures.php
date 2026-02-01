<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\BaseFixturesTrait;
use App\DataFixtures\Traits\UploadFileTrait;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserVisitorFixtures extends Fixture
{
    use BaseFixturesTrait {
        loadData as origLoadData;
    }
    use UploadFileTrait;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        string $projectDir,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
        $this->projectDir = $projectDir;
    }

    public function load(ObjectManager $manager): void
    {
        $this->origLoadData($manager);

        $i = 0;
        $this->createMany(UserVisitor::class, 10, function (UserVisitor $user) use (&$i) {
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);
            $user->setEmail($this->faker->email);
            $user->setPseudo('pseudo-'.$this->faker->numberBetween());
            if (0 === $i) {
                $user->setFirstName('User');
                $user->setLastName('Kop');
                $user->setEmail('user@kop.fr');
                $user->setPseudo('kop-69');
            }
            $user->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));
            $user->validate();
            ++$i;
        });

        $this->manager->flush();
    }
}
