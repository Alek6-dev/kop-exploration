<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Cosmetic\Domain\Enum\TypeCosmeticEnum;
use App\Cosmetic\Infrastructure\Doctrine\Entity\Cosmetic;
use App\DataFixtures\Traits\BaseFixturesTrait;
use App\DataFixtures\Traits\UploadFileTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Vich\UploaderBundle\Handler\UploadHandler;

final class CosmeticFixtures extends Fixture
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

        $this->createMany(Cosmetic::class, 20, function (Cosmetic $cosmetic) {
            $cosmetic->setName($this->faker->name);
            $cosmetic->setDescription($this->faker->text);
            $cosmetic->setColor($this->faker->hexColor());
            $cosmetic->setPrice($this->faker->numberBetween(0, 300));
            $cosmetic->setType($this->faker->randomElement(TypeCosmeticEnum::cases()));
            $cosmetic->setImage1File($this->getUploadedFile('/src/DataFixtures/assets/cosmetic', 'cosmetic-1'));
            $cosmetic->setImage2File($this->getUploadedFile('/src/DataFixtures/assets/cosmetic', 'cosmetic-2'));
            $this->handler->upload($cosmetic, 'image1File');
            $this->handler->upload($cosmetic, 'image2File');
        });

        $this->manager->flush();
    }
}
