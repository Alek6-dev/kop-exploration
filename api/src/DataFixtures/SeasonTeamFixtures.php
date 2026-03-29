<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\BaseFixturesTrait;
use App\Season\Infrastructure\Doctrine\Entity\Season;
use App\Season\Infrastructure\Doctrine\Entity\SeasonTeam;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class SeasonTeamFixtures extends Fixture implements DependentFixtureInterface
{
    use BaseFixturesTrait {
        loadData as origLoadData;
    }

    public function load(ObjectManager $manager): void
    {
        $this->origLoadData($manager);
        /** @var array<TeamInterface> $teamsAvailable */
        $teamsAvailable = $this->getReferencesByEntity(Team::class);
        $i = 0;
        $this->createMany(SeasonTeam::class, 2, function (SeasonTeam $seasonTeam) use ($teamsAvailable, &$i) {
            $seasonTeam->setSeason($this->faker->randomElement($this->getReferencesByEntity(Season::class)));
            /* @var TeamInterface $team */
            $seasonTeam->setTeam($teamsAvailable[$i]);
            ++$i;
        });

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SeasonFixtures::class,
            TeamFixtures::class,
        ];
    }
}
