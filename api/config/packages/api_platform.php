<?php

declare(strict_types=1);

use App\Bid\Domain\Exception\BidException;
use App\Championship\Domain\Exception\ChampionshipException;
use App\Driver\Domain\Exception\DriverException;
use App\Parameter\Domain\Exception\ParameterException;
use App\Player\Domain\Exception\PlayerException;
use App\Season\Domain\Exception\SeasonException;
use App\SeasonGame\Domain\Exception\SeasonGameException;
use App\Shared\Domain\Exception\EmailException;
use App\Team\Domain\Exception\TeamException;
use App\User\Domain\Exception\UserVisitorException;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Webmozart\Assert\InvalidArgumentException;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('api_platform', config: [
        'mapping' => [
            'paths' => [
                '%kernel.project_dir%/src/Bid/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/Bonus/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/Championship/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/Cosmetic/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/CreditWallet/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/Driver/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/Duel/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/Player/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/Parameter/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/Result/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/Season/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/SeasonGame/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/Strategy/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/Team/Infrastructure/ApiPlatform/Resource/',
                '%kernel.project_dir%/src/User/Infrastructure/ApiPlatform/Resource/',
            ],
        ],
        'formats' => [
            'json' => ['application/json'],
            'jsonld' => ['application/ld+json'],
            'multipart' => ['multipart/form-data'],
        ],
        'patch_formats' => [
            'json' => ['application/merge-patch+json'],
        ],
        'swagger' => [
            'versions' => [3],
            'api_keys' => [
                'JWT' => [
                    'name' => 'Authorization',
                    'type' => 'header',
                ],
            ],
        ],
        'exception_to_status' => [
            BidException::class => 400,
            ChampionshipException::class => 400,
            DriverException::class => 400,
            EmailException::class => 400,
            InvalidArgumentException::class => 400,
            ParameterException::class => 400,
            PlayerException::class => 400,
            SeasonException::class => 400,
            SeasonGameException::class => 400,
            TeamException::class => 400,
            UserVisitorException::class => 400,
        ],
        'keep_legacy_inflector' => false,
        'defaults' => [
            'stateless' => true,
            'cache_headers' => ['vary' => ['Content-Type', 'Authorization', 'Origin'],
            ],
        ],
    ]);
};
