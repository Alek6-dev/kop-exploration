<?php

declare(strict_types=1);

use App\Admin\Infrastructure\HttpController\Crud\UserVisitorCrudController;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;
use App\User\Infrastructure\ApiPlatform\State\Processor\CreateUserVisitorProcessor;
use App\User\Infrastructure\ApiPlatform\State\Processor\ForgotPasswordProcessor;
use App\User\Infrastructure\ApiPlatform\State\Processor\UpdateUserVisitorProcessor;
use App\User\Infrastructure\Doctrine\Repository\DoctrineUserVisitorRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services->load('App\\User\\', dirname(__DIR__, 3).'/src/User');

    // processors
    $services->set(ForgotPasswordProcessor::class)
        ->autoconfigure()
        ->tag('api_platform.state_processor', ['priority' => 1])
        ->bind('$forgotPasswordFrontUrl', env('URL_FRONT_FORGOT_PASSWORD'))
    ;
    $services->set(CreateUserVisitorProcessor::class)
        ->autoconfigure()
        ->tag('api_platform.state_processor', ['priority' => 1])
        ->bind('$userDirectoryImage', param('$userDirectoryImage'))
        ->bind('$projectDir', '%kernel.project_dir%')
        ->bind('$validRegistrationFrontUrl', env('URL_FRONT_VALID_REGISTRATION'))
    ;

    $services->set(UpdateUserVisitorProcessor::class)
        ->autoconfigure()
        ->tag('api_platform.state_processor', ['priority' => 1])
        ->bind('$userDirectoryImage', param('$userDirectoryImage'))
        ->bind('$projectDir', '%kernel.project_dir%')
    ;

    // handlers
    $services->set(UserVisitorCrudController::class)
        ->autoconfigure()
        ->bind('$validRegistrationFrontUrl', env('URL_FRONT_VALID_REGISTRATION'))
    ;

    // repositories
    $services->set(UserVisitorRepositoryInterface::class)
        ->class(DoctrineUserVisitorRepository::class);
};
