<?php

declare(strict_types=1);

use App\User\Infrastructure\Security\UserChecker;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('security', [
        'password_hashers' => [
            PasswordAuthenticatedUserInterface::class => 'auto',
        ],
        'providers' => [
            'app_user_provider' => [
                'entity' => [
                    'class' => App\User\Infrastructure\Doctrine\Entity\UserVisitor::class,
                    'property' => 'email',
                ],
            ],
            'app_admin_provider' => [
                'entity' => [
                    'class' => App\Admin\Infrastructure\Doctrine\Entity\UserAdmin::class,
                    'property' => 'email',
                ],
            ],
        ],
        'firewalls' => [
            'dev' => [
                'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
                'security' => false,
            ],
            'admin' => [
                'lazy' => true,
                'pattern' => '^/admin',
                'provider' => 'app_admin_provider',
                'form_login' => [
                    'login_path' => 'admin_login',
                    'check_path' => 'admin_login',
                    'enable_csrf' => true,
                ],
                'logout' => [
                    'path' => '/admin/deconnexion',
                    'target' => 'admin_login',
                ],
            ],
            'api' => [
                'pattern' => '^/api/',
                'stateless' => true,
                'provider' => 'app_user_provider',
                'jwt' => null,
            ],
            'main' => [
                'stateless' => true,
                'provider' => 'app_user_provider',
                'user_checker' => UserChecker::class,
                'json_login' => [
                    'check_path' => 'auth',
                    'username_path' => 'email',
                    'password_path' => 'password',
                    'success_handler' => 'lexik_jwt_authentication.handler.authentication_success',
                    'failure_handler' => 'lexik_jwt_authentication.handler.authentication_failure',
                ],
                'jwt' => null,
            ],
        ],
        'role_hierarchy' => [
            'ROLE_SUPER_ADMIN' => ['ROLE_ADMIN'],
        ],
        'access_control' => [
            ['path' => '^/admin/mot-de-passe-oublie', 'roles' => ['PUBLIC_ACCESS']],
            ['path' => '^/admin/connexion', 'roles' => ['PUBLIC_ACCESS']],
            ['path' => '^/admin', 'roles' => ['ROLE_ADMIN']],
            ['path' => '^/auth', 'roles' => ['PUBLIC_ACCESS']],
            ['path' => '^/api', 'roles' => ['PUBLIC_ACCESS']],
            ['path' => '^/', 'roles' => ['PUBLIC_ACCESS']],
        ],
    ]);
    if ('test' === $containerConfigurator->env()) {
        $containerConfigurator->extension('security', [
            'password_hashers' => [
                PasswordAuthenticatedUserInterface::class => [
                    'algorithm' => 'auto',
                    'cost' => 4,
                    'time_cost' => 3,
                    'memory_cost' => 10,
                ],
            ],
        ]);
    }
};
