<?php

declare(strict_types=1);

use App\Driver\Domain\Repository\DriverRepositoryInterface;
use App\Driver\Infrastructure\Doctrine\Repository\DoctrineDriverRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Driver\\', dirname(__DIR__, 3).'/src/Driver');

    // providers
    $services->set(App\Driver\Infrastructure\ApiPlatform\State\Provider\DriverItemProvider::class)
        ->autoconfigure()
        ->tag('api_platform.state_provider', ['priority' => 1]);

    $services->set(App\Driver\Infrastructure\ApiPlatform\State\Provider\DriverCollectionProvider::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_provider', ['priority' => 0]);

    // processors
    $services->set(App\Driver\Infrastructure\ApiPlatform\State\Processor\CreateDriverProcessor::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 1]);

    $services->set(App\Driver\Infrastructure\ApiPlatform\State\Processor\UpdateDriverProcessor::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 1]);

    $services->set(App\Driver\Infrastructure\ApiPlatform\State\Processor\DeleteDriverProcessor::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);

    // repositories
    $services->set(DriverRepositoryInterface::class)
        ->class(DoctrineDriverRepository::class);
};
