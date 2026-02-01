<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\BaseFixturesTrait;
use App\DataFixtures\Traits\UploadFileTrait;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Vich\UploaderBundle\Handler\UploadHandler;

final class DriverFixtures extends Fixture implements DependentFixtureInterface
{
    use BaseFixturesTrait {
        loadData as origLoadData;
    }
    use UploadFileTrait;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        string $projectDir,
        private readonly UploadHandler $handler,
    ) {
        $this->projectDir = $projectDir;
    }

    public function load(ObjectManager $manager): void
    {
        $this->origLoadData($manager);

        $this->createMany(Driver::class, 16, function (Driver $driver) {
            $driver->setFirstName($this->faker->firstName);
            $driver->setLastName($this->faker->lastName);
            $driver->setIsReplacement(false);
            $driver->setMinValue($this->faker->numberBetween(10, 85));
            $driver->setTeam($this->faker->randomElement($this->getReferencesByEntity(Team::class)));
            $driver->setImageFile($this->getUploadedFile('/src/DataFixtures/assets/driver', 'driver'));
            $this->handler->upload($driver, 'imageFile');
        });

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TeamFixtures::class,
        ];
    }
}
