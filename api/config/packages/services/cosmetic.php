<?php

declare(strict_types=1);

use App\Cosmetic\Domain\Repository\CosmeticRepositoryInterface;
use App\Cosmetic\Infrastructure\Doctrine\Repository\DoctrineCosmeticRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Cosmetic\\', dirname(__DIR__, 3).'/src/Cosmetic');

    // providers
    $services->set(App\Cosmetic\Infrastructure\ApiPlatform\State\Provider\CosmeticItemProvider::class)
        ->autoconfigure()
        ->tag('api_platform.state_provider', ['priority' => 1]);

    $services->set(App\Cosmetic\Infrastructure\ApiPlatform\State\Provider\CosmeticCollectionProvider::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_provider', ['priority' => 0]);

    // repositories
    $services->set(CosmeticRepositoryInterface::class)
        ->class(DoctrineCosmeticRepository::class);
};
