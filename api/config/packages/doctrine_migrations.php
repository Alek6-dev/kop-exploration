<?php

declare(strict_types=1);

return static function (Symfony\Config\DoctrineMigrationsConfig $doctrineMigrationsConfig): void {
    $doctrineMigrationsConfig->migrationsPath('App\Migrations', '%kernel.project_dir%/config/database/migrations');
    $doctrineMigrationsConfig->enableProfiler('%kernel.debug%');
};
