<?php

declare(strict_types=1);

use App\Result\Domain\Repository\ResultRepositoryInterface;
use App\Result\Infrastructure\Doctrine\Repository\DoctrineResultRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Result\\', dirname(__DIR__, 3).'/src/Result');

    // repositories
    $services->set(ResultRepositoryInterface::class)
        ->class(DoctrineResultRepository::class);
};
