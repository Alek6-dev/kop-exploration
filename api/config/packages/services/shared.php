<?php

declare(strict_types=1);

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use App\Shared\Infrastructure\ApiPlatform\OpenApi\OpenApiDecorator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Shared\\', dirname(__DIR__, 3).'/src/Shared')
        ->exclude([dirname(__DIR__, 3).'/src/Shared/Infrastructure/Symfony/Kernel.php']);

    // Decorate OpenAPI factory to add global documentation
    $services->set(OpenApiDecorator::class)
        ->decorate(OpenApiFactoryInterface::class)
        ->args([service('.inner')]);
};
