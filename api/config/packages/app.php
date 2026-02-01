<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure()
        ->bind('$emailSenderAddress', param('mailer.sender_email'))
        ->bind('$emailSenderName', param('mailer.sender_name'))
        ->bind('$forgotPasswordFrontUrl', env('URL_FRONT_FORGOT_PASSWORD'))
        ->bind('$validRegistrationFrontUrl', env('URL_FRONT_VALID_REGISTRATION'))
    ;

    $services->load('App\\', '%kernel.project_dir%/src/*')
        ->exclude([
            '%kernel.project_dir%/src/{Domain,Infrastructure}/{Tests,Controller/Common,*/DTO/*DTO*,Doctrine/Entity}',
        ]);
};
