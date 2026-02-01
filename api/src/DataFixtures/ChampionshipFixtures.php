<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Championship\Domain\Enum\ChampionshipNumberPlayerEnum;
use App\Championship\Domain\Enum\ChampionshipNumberRaceEnum;
use App\Championship\Infrastructure\Doctrine\Entity\Championship;
use App\DataFixtures\Traits\BaseFixturesTrait;
use App\DataFixtures\Traits\UploadFileTrait;
use App\Parameter\Domain\Repository\ParameterRepositoryInterface;
use App\Season\Infrastructure\Doctrine\Entity\Season;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class ChampionshipFixtures extends Fixture implements DependentFixtureInterface
{
    use BaseFixturesTrait {
        loadData as origLoadData;
    }
    use UploadFileTrait;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        string $projectDir,
        private readonly ParameterRepositoryInterface $parameterRepository,
    ) {
        $this->projectDir = $projectDir;
    }

    public function load(ObjectManager $manager): void
    {
        $this->origLoadData($manager);

        $initialBudgetParam = $this->parameterRepository->getParameterByCode('player_initial_budget');

        $this->createMany(Championship::class, 16, function (Championship $championship) use ($initialBudgetParam) {
            $championship->setSeason($this->faker->randomElement($this->getReferencesByEntity(Season::class)))
                ->setCreatedBy($this->faker->randomElement($this->getReferencesByEntity(UserVisitor::class)))
                ->setName($this->faker->text(30))
                ->setJokerEnabled($this->faker->boolean)
                ->setNumberOfRaces($this->faker->boolean ? ChampionshipNumberRaceEnum::TEN_RACES : ChampionshipNumberRaceEnum::FOUR_RACES)
                ->setNumberOfPlayers($this->faker->boolean ? ChampionshipNumberPlayerEnum::TEN_PLAYERS : ChampionshipNumberPlayerEnum::FOUR_PLAYERS)
                ->setInvitationCode($this->faker->text(20))
                ->setInitialBudget((int) $initialBudgetParam->getValue())
            ;
            $initialUsageDriver = 0;
            switch ($championship->getChampionshipRaces()) {
                case ChampionshipNumberRaceEnum::FOUR_RACES:
                    $initialUsageDriver = 3;
                    break;
                case ChampionshipNumberRaceEnum::FIVE_RACES:
                case ChampionshipNumberRaceEnum::SIX_RACES:
                    $initialUsageDriver = 4;
                    break;
                case ChampionshipNumberRaceEnum::SEVEN_RACES:
                case ChampionshipNumberRaceEnum::HEIGHT_RACES:
                    $initialUsageDriver = 5;
                    break;
                case ChampionshipNumberRaceEnum::NINE_RACES:
                case ChampionshipNumberRaceEnum::TEN_RACES:
                    $initialUsageDriver = 6;
                    break;
            }
            $championship->setInitialUsageDriver($initialUsageDriver);
        });

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SeasonFixtures::class,
            UserVisitorFixtures::class,
            ParameterFixtures::class,
        ];
    }
}
