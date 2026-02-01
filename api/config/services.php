<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__.'/packages/app.php');
    $containerConfigurator->import(__DIR__.'/packages/services/');
    $containerConfigurator->import(__DIR__.'/parameters.yaml');

    $parameters = $containerConfigurator->parameters();
    $parameters->set('$emailSenderAddress', '%mailer.sender_email%');
    $parameters->set('$emailSenderName', '%mailer.sender_name%');
    $parameters->set('$forgotPasswordFrontUrl', env('URL_FRONT_FORGOT_PASSWORD'));
    $parameters->set('$userDirectoryImage', '/uploads/images/avatar/');
    $parameters->set('locale', 'fr');
    $parameters->set('site_name', 'kop');
    $parameters->set('site_name', 'kop');
    $parameters->set('router.request_context.host', '%request_context.host%');
    $parameters->set('router.request_context.scheme', '%request_context.scheme%');
    $parameters->set('router.request_context.base_url', '%request_context.base_url%');
    $parameters->set('asset.request_context.base_path', '%request_context.base_path%');
    $parameters->set('asset.request_context.secure', '%request_context.secure%');
    $parameters->set('site_url_no_scheme', '%request_context.host%');
    $parameters->set('site_url', '%request_context.scheme%://%request_context.host%');
};
