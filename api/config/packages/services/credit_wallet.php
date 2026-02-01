<?php

declare(strict_types=1);

use App\CreditWallet\Domain\Repository\CreditPackRepositoryInterface;
use App\CreditWallet\Domain\Repository\CreditWalletRepositoryInterface;
use App\CreditWallet\Infrastructure\Doctrine\Repository\DoctrineCreditPackRepository;
use App\CreditWallet\Infrastructure\Doctrine\Repository\DoctrineCreditWalletRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\CreditWallet\\', dirname(__DIR__, 3).'/src/CreditWallet');

    // repositories
    $services->set(CreditWalletRepositoryInterface::class)
        ->class(DoctrineCreditWalletRepository::class);
    $services->set(CreditPackRepositoryInterface::class)
        ->class(DoctrineCreditPackRepository::class);
};
