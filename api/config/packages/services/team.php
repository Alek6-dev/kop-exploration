<?php

declare(strict_types=1);

use App\Team\Domain\Repository\TeamRepositoryInterface;
use App\Team\Infrastructure\Doctrine\Repository\DoctrineTeamRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Team\\', dirname(__DIR__, 3).'/src/Team');

    // repositories
    $services->set(TeamRepositoryInterface::class)
        ->class(DoctrineTeamRepository::class);
};
