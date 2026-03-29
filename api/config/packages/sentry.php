<?php

declare(strict_types=1);

use Sentry\Integration\IgnoreErrorsIntegration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('sentry', [
        'dsn' => '%env(SENTRY_DSN)%',
        'options' => [
            'environment' => '%env(SENTRY_ENV)%',
            'integrations' => [
                IgnoreErrorsIntegration::class,
            ],
        ],
    ]);

    $containerConfigurator->services()->set(IgnoreErrorsIntegration::class)
        ->args([
            '$options' => [
                'ignore_exceptions' => [
                    AccessDeniedException::class,
                    NotFoundHttpException::class,
                    UnprocessableEntityHttpException::class,
                ],
            ],
        ]
        );
};
