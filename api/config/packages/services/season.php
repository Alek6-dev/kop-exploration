<?php

declare(strict_types=1);

use App\Season\Domain\Repository\SeasonRaceRepositoryInterface;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonRaceRepository;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Season\\', dirname(__DIR__, 3).'/src/Season');

    // repositories
    $services->set(SeasonRepositoryInterface::class)
        ->class(DoctrineSeasonRepository::class);
    $services->set(SeasonRaceRepositoryInterface::class)
        ->class(DoctrineSeasonRaceRepository::class);
};
