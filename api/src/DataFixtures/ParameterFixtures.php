<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Parameter\Domain\Enum\TypeEnum;
use App\Parameter\Infrastructure\Doctrine\Entity\Parameter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ObjectManager;

final class ParameterFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        try {
            $parameter = new Parameter();
            $parameter->setCode('player_initial_budget')
                ->setLabel('Budget initial des joueurs')
                ->setType(TypeEnum::NUMBER)
                ->setValue('350');
            $manager->persist($parameter);

            $manager->flush();
        } catch (UniqueConstraintViolationException) {
            // If parameters already exist, no need to recreate them
        }
    }
}
