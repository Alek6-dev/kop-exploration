<?php

namespace App\DataFixtures\Traits;

use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

trait BaseFixturesTrait
{
    protected Generator $faker;

    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->loadData($manager);
    }

    public function loadData(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
    }

    protected function createMany(string $className, int $count, callable $factory): void
    {
        for ($i = 0; $i < $count; ++$i) {
            $entity = new $className();
            $factory($entity);
            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference($className.'_'.$i, $entity);
        }
    }

    /**
     * @return array<int, object>
     *
     * @throws \Exception
     */
    protected function getReferencesByEntity(string $entityName): array
    {
        if (!class_exists($entityName)) {
            throw new \Exception('Entity: '.$entityName.' not found !');
        }
        $i = 0;
        $references = [];
        while ($this->hasReference($entityName.'_'.$i, $entityName)) {
            $references[] = $this->getReference($entityName.'_'.$i, $entityName);
            ++$i;
        }

        return $references;
    }
}
