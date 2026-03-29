<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Infrastructure\Doctrine\Entity\Championship;
use App\DataFixtures\Traits\BaseFixturesTrait;
use App\DataFixtures\Traits\UploadFileTrait;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class PlayerFixtures extends Fixture implements DependentFixtureInterface
{
    use BaseFixturesTrait {
        loadData as origLoadData;
    }
    use UploadFileTrait;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        string $projectDir,
    ) {
        $this->projectDir = $projectDir;
    }

    public function load(ObjectManager $manager): void
    {
        $this->origLoadData($manager);

        $this->createMany(Player::class, 32, function (Player $player) {
            /** @var ChampionshipInterface $championship */
            $championship = $this->faker->randomElement($this->getReferencesByEntity(Championship::class));
            /** @var UserVisitorInterface $user */
            $user = $this->faker->randomElement($this->getReferencesByEntity(UserVisitor::class));
            $player->setChampionship($championship)
                ->setUser($user)
                ->setName($user->getPseudo())
                ->setRemainingBudget($championship->getInitialBudget())
                ->setRemainingUsageDriver1($championship->getInitialUsageDriver())
                ->setRemainingUsageDriver2($championship->getInitialUsageDriver())
                ->setRemainingDuelUsageDriver1($championship->getInitialUsageDriver())
                ->setRemainingDuelUsageDriver2($championship->getInitialUsageDriver())
            ;
        });

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ChampionshipFixtures::class,
            UserVisitorFixtures::class,
        ];
    }
}
