<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\BaseFixturesTrait;
use App\DataFixtures\Traits\UploadFileTrait;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Vich\UploaderBundle\Handler\UploadHandler;

final class TeamFixtures extends Fixture
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

        $this->createMany(Team::class, 4, function (Team $team) {
            $team->setName($this->faker->name);
            $team->setMinValue($this->faker->numberBetween(10, 85));
            $team->setColor($this->faker->hexColor());
            $team->setImageFile($this->getUploadedFile('/src/DataFixtures/assets/user', 'team'));
            $this->handler->upload($team, 'imageFile');
        });

        $this->manager->flush();
    }
}
