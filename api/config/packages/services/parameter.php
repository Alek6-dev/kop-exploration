<?php

declare(strict_types=1);

use App\Parameter\Domain\Repository\ParameterRepositoryInterface;
use App\Parameter\Infrastructure\Doctrine\Repository\DoctrineParameterRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Parameter\\', dirname(__DIR__, 3).'/src/Parameter');

    // repositories
    $services->set(ParameterRepositoryInterface::class)
        ->class(DoctrineParameterRepository::class);
};
