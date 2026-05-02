<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('doctrine', [
        'dbal' => [
            'dbname' => '%database_dbname%',
            'host' => '%database_host%',
            'port' => '%database_port%',
            'user' => '%database_user%',
            'password' => '%database_password%',
            'driver' => 'pdo_mysql',
            'charset' => 'utf8mb4',
            'default_table_options' => [
                'charset' => 'utf8mb4',
                'collate' => 'utf8mb4_unicode_ci',
            ],
            'options' => [1002 => 'SET sql_mode=(SELECT REPLACE(@@sql_mode, "ONLY_FULL_GROUP_BY", ""))'],
        ],
        'orm' => [
            'report_fields_where_declared' => true,
            'auto_generate_proxy_classes' => true,
            'enable_lazy_ghost_objects' => true,
            'naming_strategy' => 'doctrine.orm.naming_strategy.underscore_number_aware',
            'auto_mapping' => true,
            'mappings' => [
                'Admin' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Admin/Infrastructure',
                    'prefix' => 'App\Admin\Infrastructure',
                ],
                'Bid' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Bid/Infrastructure',
                    'prefix' => 'App\Bid\Infrastructure',
                ],
                'Bonus' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Bonus/Infrastructure',
                    'prefix' => 'App\Bonus\Infrastructure',
                ],
                'Championship' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Championship/Infrastructure',
                    'prefix' => 'App\Championship\Infrastructure',
                ],
                'Cosmetic' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Cosmetic/Infrastructure',
                    'prefix' => 'App\Cosmetic\Infrastructure',
                ],
                'CreditWallet' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/CreditWallet/Infrastructure',
                    'prefix' => 'App\CreditWallet\Infrastructure',
                ],
                'Driver' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Driver/Infrastructure',
                    'prefix' => 'App\Driver\Infrastructure',
                ],
                'Duel' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Duel/Infrastructure',
                    'prefix' => 'App\Duel\Infrastructure',
                ],
                'Parameter' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Parameter/Infrastructure',
                    'prefix' => 'App\Parameter\Infrastructure',
                ],
                'Player' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Player/Infrastructure',
                    'prefix' => 'App\Player\Infrastructure',
                ],
                'Performance' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Performance/Infrastructure',
                    'prefix' => 'App\Performance\Infrastructure',
                ],
                'Race' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Race/Infrastructure',
                    'prefix' => 'App\Race\Infrastructure',
                ],
                'Result' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Result/Infrastructure',
                    'prefix' => 'App\Result\Infrastructure',
                ],
                'Season' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Season/Infrastructure',
                    'prefix' => 'App\Season\Infrastructure',
                ],
                'SeasonGame' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/SeasonGame/Infrastructure',
                    'prefix' => 'App\SeasonGame\Infrastructure',
                ],
                'Shared' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Shared/Infrastructure',
                    'prefix' => 'App\Shared\Infrastructure',
                ],
                'Strategy' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Strategy/Infrastructure',
                    'prefix' => 'App\Strategy\Infrastructure',
                ],
                'Team' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/Team/Infrastructure',
                    'prefix' => 'App\Team\Infrastructure',
                ],
                'User' => [
                    'is_bundle' => false,
                    'type' => 'attribute',
                    'dir' => '%kernel.project_dir%/src/User/Infrastructure',
                    'prefix' => 'App\User\Infrastructure',
                ],
            ],
        ],
    ]);
    if ('prod' === $containerConfigurator->env()) {
        $containerConfigurator->extension('doctrine', [
            'orm' => [
                'auto_generate_proxy_classes' => false,
                'proxy_dir' => '%kernel.build_dir%/doctrine/orm/Proxies',
                'query_cache_driver' => [
                    'type' => 'pool',
                    'pool' => 'doctrine.system_cache_pool',
                ],
                'result_cache_driver' => [
                    'type' => 'pool',
                    'pool' => 'doctrine.result_cache_pool',
                ],
            ],
        ]);
        $containerConfigurator->extension('framework', [
            'cache' => [
                'pools' => [
                    'doctrine.result_cache_pool' => [
                        'adapter' => 'cache.app',
                    ],
                    'doctrine.system_cache_pool' => [
                        'adapter' => 'cache.system',
                    ],
                ],
            ],
        ]);
    }
};
