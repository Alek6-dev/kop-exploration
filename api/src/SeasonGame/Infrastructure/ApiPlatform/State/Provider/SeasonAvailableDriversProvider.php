<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SeasonAvailableDriversProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        /** @var Driver[] $drivers */
        $drivers = $this->em->createQuery(
            'SELECT d FROM App\Driver\Infrastructure\Doctrine\Entity\Driver d
             JOIN d.team t
             WHERE d.isReplacement = false
             AND d.replacedPermanently = false
             ORDER BY t.name ASC, d.lastName ASC'
        )->getResult();

        return array_map(fn (Driver $d) => [
            'uuid' => $d->getUuid(),
            'name' => $d->getName(),
            'minValue' => $d->getMinValue(),
            'teamName' => $d->getTeam()?->getName(),
            'teamColor' => $d->getTeam()?->getColor(),
            'image' => $d->getRelativeImagePath(),
        ], $drivers);
    }
}
