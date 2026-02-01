<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\MonologConfig;

return static function (ContainerConfigurator $containerConfigurator, MonologConfig $monolog): void {
    $monolog->channels(['deprecation', 'mail']);

    $monolog->handler('main')
        ->type('rotating_file')
        ->path('%kernel.logs_dir%/%kernel.environment%.all.log')
        ->level('info')
        ->maxFiles(3)
        ->channels()->elements(['!deprecation']);

    $monolog->handler('console')
        ->type('console')
        ->processPsr3Messages(false)
        ->channels()->elements(['!event',
            '!doctrine',
            '!console',
            '!mail',
            '!deprecation',
        ]);

    $monolog->handler('mail')
        ->type('rotating_file')
        ->maxFiles(3)
        ->path('%kernel.logs_dir%/%kernel.environment%.mail.log')
        ->level('debug')
        ->channels()->elements(['mail']);

    $monolog->handler('deprecation')
        ->type('rotating_file')
        ->maxFiles(3)
        ->path('%kernel.logs_dir%/%kernel.environment%.deprecation.log')
        ->level('debug')
        ->channels()->elements(['deprecation']);

    $monolog->handler('login')
        ->type('rotating_file')
        ->maxFiles(3)
        ->path('%kernel.logs_dir%/%kernel.environment%.auth.log')
        ->level('debug')
        ->channels()->elements(['security']);

    $monolog->handler('main_error')
        ->type('fingers_crossed')
        ->actionLevel('error')
        ->handler('streamed_error')
        ->channels()->elements(['!deprecation']);

    $monolog->handler('streamed_error')
        ->type('rotating_file')
        ->maxFiles(15)
        ->path('%kernel.logs_dir%/%kernel.environment%.error.log')
        ->level('info')
        ->handler('streamed_error')
        ->channels()->elements(['!deprecation']);

    $monolog->handler('main_critical')
        ->type('fingers_crossed')
        ->actionLevel('critical')
        ->handler('grouped_critical')
        ->channels()->elements(['!deprecation']);

    $monolog->handler('grouped_critical')
        ->type('group')
        ->members([
            'streamed_critical',
            'buffered_critical',
        ]);

    $monolog->handler('streamed_critical')
        ->type('rotating_file')
        ->maxFiles(15)
        ->path('%kernel.logs_dir%/%kernel.environment%.critical.log')
        ->level('critical')
        ->channels()->elements(['!deprecation']);

    $monolog->handler('buffered_critical')
        ->type('buffer')
        ->handler('mail');

    if ('test' === $containerConfigurator->env()) {
        $mainTestHandler = $monolog->handler('main')
            ->type('fingers_crossed')
            ->actionLevel('error')
            ->handler('nested')
        ;
        $mainTestHandler->channels()->elements(['!event']);

        $monolog->handler('nested')
            ->type('stream')
            ->path('%kernel.logs_dir%/%kernel.environment%.log')
            ->level('debug')
        ;
    }
    if ('prod' === $containerConfigurator->env()) {
        $mainProdHandler = $monolog->handler('main')
            ->type('fingers_crossed')
            ->actionLevel('error')
            ->handler('nested')
            ->bufferSize(50)
        ;

        $monolog->handler('nested')
            ->type('stream')
            ->path('php://stderr')
            ->level('debug')
            ->formatter('monolog.formatter.json')
        ;

        $monolog->handler('console')
            ->type('console')
            ->processPsr3Messages(false)
            ->channels()->elements([
                '!event',
                '!doctrine',
            ]);

        $monolog->handler('deprecation')
            ->type('stream')
            ->processPsr3Messages(false)
            ->path('php://stderr')
            ->channels()->elements([
                'deprecation',
            ]);
    }
};
