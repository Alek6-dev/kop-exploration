<?php

declare(strict_types=1);

use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Championship\Infrastructure\Doctrine\Repository\DoctrineChampionshipRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Championship\\', dirname(__DIR__, 3).'/src/Championship');

    // repositories
    $services->set(ChampionshipRepositoryInterface::class)
        ->class(DoctrineChampionshipRepository::class);
};
