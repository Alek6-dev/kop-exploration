<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('vich_uploader', [
        'db_driver' => 'orm',
        'metadata' => [
            'type' => 'attribute',
        ],
        'mappings' => [
            'default' => [
                'uri_prefix' => '/uploads/images/default',
                'upload_destination' => '%kernel.project_dir%/public/uploads/images/default',
                'namer' => Vich\UploaderBundle\Naming\SmartUniqueNamer::class,
                'inject_on_load' => true,
            ],
            'avatar' => [
                'uri_prefix' => '/uploads/images/avatar',
                'upload_destination' => '%kernel.project_dir%/public/uploads/images/avatar',
                'namer' => Vich\UploaderBundle\Naming\SmartUniqueNamer::class,
                'inject_on_load' => true,
            ],
            'cosmetic' => [
                'uri_prefix' => '/uploads/images/cosmetic',
                'upload_destination' => '%kernel.project_dir%/public/uploads/images/cosmetic',
                'namer' => Vich\UploaderBundle\Naming\SmartUniqueNamer::class,
                'inject_on_load' => true,
            ],
            'driver' => [
                'uri_prefix' => '/uploads/images/driver',
                'upload_destination' => '%kernel.project_dir%/public/uploads/images/driver',
                'namer' => Vich\UploaderBundle\Naming\SmartUniqueNamer::class,
                'inject_on_load' => true,
            ],
            'team' => [
                'uri_prefix' => '/uploads/images/team',
                'upload_destination' => '%kernel.project_dir%/public/uploads/images/team',
                'namer' => Vich\UploaderBundle\Naming\SmartUniqueNamer::class,
                'inject_on_load' => true,
            ],
            'bonus' => [
                'uri_prefix' => '/uploads/images/bonus',
                'upload_destination' => '%kernel.project_dir%/public/uploads/images/bonus',
                'namer' => Vich\UploaderBundle\Naming\SmartUniqueNamer::class,
                'inject_on_load' => true,
            ],
        ],
    ]);
};
