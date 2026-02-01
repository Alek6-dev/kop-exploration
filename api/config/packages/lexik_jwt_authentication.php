<?php

declare(strict_types=1);

use App\Shared\Infrastructure\EventListener\JWTCreatedListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('lexik_jwt_authentication', [
        'secret_key' => '%env(resolve:JWT_SECRET_KEY)%',
        'public_key' => '%env(resolve:JWT_PUBLIC_KEY)%',
        'pass_phrase' => '%env(JWT_PASSPHRASE)%',
        'token_ttl' => 604800, // 1 week
    ]);

    $containerConfigurator->services()
        ->set('authentication_api.event.jwt_created_listener')
        ->class(JWTCreatedListener::class)
        ->tag('kernel.event_listener', [
            'event' => 'lexik_jwt_authentication.on_jwt_created',
            'method' => 'onJWTCreated',
        ]);
};
