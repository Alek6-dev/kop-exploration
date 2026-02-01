<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\BaseFixturesTrait;
use App\Season\Infrastructure\Doctrine\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use function Symfony\Component\Clock\now;

final class SeasonFixtures extends Fixture
{
    use BaseFixturesTrait {
        loadData as origLoadData;
    }

    public function load(ObjectManager $manager): void
    {
        $this->origLoadData($manager);
        $seasonAlreadyActive = false;
        $this->createMany(Season::class, 1, function (Season $season) use (&$seasonAlreadyActive) {
            $season->setName(now()->format('Y'));
            $isActive = false;
            if (!$seasonAlreadyActive) {
                $isActive = true;
                $seasonAlreadyActive = true;
            }
            $season->setIsActive($isActive);
        });

        $this->manager->flush();
    }
}
