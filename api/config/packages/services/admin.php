<?php

declare(strict_types=1);

use App\Admin\Domain\Repository\UserAdminRepositoryInterface;
use App\Admin\Infrastructure\Doctrine\Repository\DoctrineUserAdminRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Admin\\', dirname(__DIR__, 3).'/src/Admin');

    // repositories
    $services->set(UserAdminRepositoryInterface::class)
        ->class(DoctrineUserAdminRepository::class);
};
