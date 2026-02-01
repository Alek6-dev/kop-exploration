<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('nelmio_cors', [
        'defaults' => [
            'origin_regex' => true,
            'allow_origin' => ['*'],
            'allow_headers' => ['*'],
            'allow_methods' => ['GET', 'OPTIONS', 'POST', 'PUT', 'PATCH', 'DELETE'],
            'expose_headers' => ['Link'],
            'max_age' => 3600,
        ],
    ]);
};
