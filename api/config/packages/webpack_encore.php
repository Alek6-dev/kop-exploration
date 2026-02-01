<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\WebpackEncoreConfig;

return static function (ContainerConfigurator $containerConfigurator, WebpackEncoreConfig $encore): void {
    $encore
        ->outputPath('%kernel.project_dir%/public/assets/build')
    ;

    $encore->preload(true);
    $encore->scriptAttributes('defer', true);
    $encore->scriptAttributes('data-turbo-track', 'reload');

    $encore->linkAttributes('data-turbo-track', 'reload');
};
