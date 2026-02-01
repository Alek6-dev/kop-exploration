<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\BaseFixturesTrait;
use App\Race\Domain\Enum\CountryEnum;
use App\Race\Infrastructure\Doctrine\Entity\Race;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class RaceFixtures extends Fixture
{
    use BaseFixturesTrait {
        loadData as origLoadData;
    }

    public function load(ObjectManager $manager): void
    {
        $this->origLoadData($manager);

        $this->createMany(Race::class, 4, function (Race $race) {
            $race->setName($this->faker->name);
            $race->setCountry($this->faker->randomElement(CountryEnum::cases()));
        });

        $this->manager->flush();
    }
}
