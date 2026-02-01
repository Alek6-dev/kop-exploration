<?php

use App\Shared\Infrastructure\Symfony\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

function app(): Kernel
{
    static $kernel;
    $kernel ??= (static function () {
        $env = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'test';
        $debug = $_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? true;

        $kernel = new Kernel((string) $env, (bool) $debug);
        $kernel->boot();

        return $kernel;
    })();

    return $kernel;
}

function container(): ContainerInterface
{
    $container = app()->getContainer();

    return $container->has('test.service_container') ? $container->get('test.service_container') : $container;
}

function repository(string $className): EntityRepository
{
    return container()->get(EntityManagerInterface::class)->getRepository($className);
}
