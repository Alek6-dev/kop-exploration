<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('twig', [
        'paths' => [
            '%kernel.project_dir%/templates',
            '%kernel.project_dir%/public' => 'public',
        ],
        'debug' => '%kernel.debug%',
        'strict_variables' => '%kernel.debug%',
        'globals' => [
            'app_var' => [
                'localizeddate_format' => 'd MMMM y',
                'site_name' => 'kop',
            ],
        ],
        'form_themes' => [
            'Form/base_theme.html.twig',
            'Form/widget_specific_class_theme.html.twig',
        ],
    ]);
};
