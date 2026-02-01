<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\BaseFixturesTrait;
use App\Race\Domain\Model\RaceInterface;
use App\Race\Infrastructure\Doctrine\Entity\Race;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Infrastructure\Doctrine\Entity\Season;
use App\Season\Infrastructure\Doctrine\Entity\SeasonRace;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class SeasonRaceFixtures extends Fixture implements DependentFixtureInterface
{
    use BaseFixturesTrait {
        loadData as origLoadData;
    }

    public function load(ObjectManager $manager): void
    {
        $this->origLoadData($manager);
        /** @var array<RaceInterface> $racesAvailable */
        $racesAvailable = $this->getReferencesByEntity(Race::class);
        $i = 0;
        $this->createMany(SeasonRace::class, \count($racesAvailable), function (SeasonRace $seasonRace) use ($racesAvailable, &$i) {
            /** @var SeasonInterface $season */
            $season = $this->faker->randomElement($this->getReferencesByEntity(Season::class));
            $seasonRace->setSeason($season);
            $seasonRace->setRace($racesAvailable[$i]);
            $seasonRace->setDate(\DateTimeImmutable::createFromMutable($this->faker->dateTime()));
            $seasonRace->setLaps($this->faker->numberBetween(10, 30));
            $seasonRace->setQualificationDate(\DateTimeImmutable::createFromMutable($this->faker->dateTime()));
            $seasonRace->setLimitStrategyDate(\DateTimeImmutable::createFromMutable($this->faker->dateTime()));
            $seasonRace->setSprintDate($this->faker->boolean() ? \DateTimeImmutable::createFromMutable($this->faker->dateTime()) : null);
            ++$i;
        });

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SeasonFixtures::class,
            RaceFixtures::class,
        ];
    }
}
