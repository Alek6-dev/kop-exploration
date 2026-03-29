<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\BaseFixturesTrait;
use App\Race\Domain\Model\RaceInterface;
use App\Race\Infrastructure\Doctrine\Entity\Race;
use App\Result\Domain\Enum\TypeResultEnum;
use App\Result\Infrastructure\Doctrine\Entity\Result;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Infrastructure\Doctrine\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class ResultFixtures extends Fixture implements DependentFixtureInterface
{
    use BaseFixturesTrait {
        loadData as origLoadData;
    }

    public function load(ObjectManager $manager): void
    {
        $this->origLoadData($manager);
        $i = 0;
        /** @var SeasonInterface $season */
        foreach ($this->getReferencesByEntity(Season::class) as $season) {
            foreach (TypeResultEnum::cases() as $typeResult) {
                /** @var RaceInterface $race */
                foreach ($this->getReferencesByEntity(Race::class) as $race) {
                    if ($this->faker->boolean) {
                        continue;
                    }
                    $result = new Result();
                    $result->setSeason($season);
                    $result->setRace($race);
                    $this->manager->persist($result);
                    // store for usage later as App\Entity\ClassName_#COUNT#
                    $this->addReference(Result::class.'_'.$i, $result);
                    ++$i;
                }
            }
        }

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DriverFixtures::class,
            RaceFixtures::class,
            TeamFixtures::class,
            SeasonFixtures::class,
        ];
    }
}
