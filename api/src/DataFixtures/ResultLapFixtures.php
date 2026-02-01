<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\BaseFixturesTrait;
use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\Result\Domain\Enum\TypeResultEnum;
use App\Result\Domain\Model\ResultInterface;
use App\Result\Infrastructure\Doctrine\Entity\Result;
use App\Result\Infrastructure\Doctrine\Entity\ResultLap;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class ResultLapFixtures extends Fixture implements DependentFixtureInterface
{
    use BaseFixturesTrait {
        loadData as origLoadData;
    }

    public function load(ObjectManager $manager): void
    {
        $this->origLoadData($manager);
        $countResultLap = 0;
        /** @var ResultInterface $result */
        foreach ($this->getReferencesByEntity(Result::class) as $result) {
            $nbLapsToCreate = $this->faker->randomElement([10, 20, 30]);
            foreach (TypeResultEnum::cases() as $typeResult) {
                if (TypeResultEnum::NORMAL !== $typeResult) {
                    $nbLapsToCreate = 1;
                }
                /** @var DriverInterface $driver */
                foreach ($this->getReferencesByEntity(Driver::class) as $driver) {
                    $surrenderLap = false;
                    for ($i = 1; $i <= $nbLapsToCreate; ++$i) {
                        if ($this->faker->boolean(25)) {
                            $surrenderLap = true;
                        }
                        $resultLap = new ResultLap();
                        $resultLap->setResult($result);
                        $resultLap->setNoLap($i);
                        $resultLap->setType($typeResult);
                        $resultLap->setDriver($driver);
                        $resultLap->setPlace($surrenderLap ? 'A' : (string) $this->faker->numberBetween(1, 16));
                        $this->manager->persist($resultLap);
                        // store for usage later as App\Entity\ClassName_#COUNT#
                        $this->addReference(ResultLap::class.'_'.$countResultLap, $resultLap);
                        ++$countResultLap;
                    }
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
            ResultFixtures::class,
        ];
    }
}
