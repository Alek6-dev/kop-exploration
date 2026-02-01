<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('liip_imagine', [
        'twig' => [
            'mode' => 'lazy',
        ],
        'loaders' => [
            'default' => [
                'filesystem' => [
                    'data_root' => [
                        '%imagine.file_system_root%',
                        '%imagine.file_system_shared_root%',
                    ],
                ],
            ],
        ],
        'resolvers' => [
            'default' => [
                'web_path' => [
                    'web_root' => '%kernel.project_dir%/public',
                    'cache_prefix' => 'media/cache',
                ],
            ],
        ],
        'filter_sets' => [
            'cache' => null,
            'default_thumbnail' => [
                'quality' => 85,
                'filters' => [
                    'thumbnail' => [
                        'size' => [
                            120,
                            90,
                        ],
                        'mode' => 'inset',
                        'allow_upscale' => true,
                    ],
                ],
            ],
            'medium_thumbnail' => [
                'quality' => 85,
                'filters' => [
                    'thumbnail' => [
                        'size' => [
                            200,
                            200,
                        ],
                        'mode' => 'inset',
                        'allow_upscale' => true,
                    ],
                ],
            ],
            'large_thumbnail' => [
                'quality' => 85,
                'filters' => [
                    'thumbnail' => [
                        'size' => [
                            240,
                            240,
                        ],
                        'mode' => 'inset',
                        'allow_upscale' => true,
                    ],
                ],
            ],
        ],
    ]);
};
