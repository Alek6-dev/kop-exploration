<?php

namespace Deployer;

import('recipe/symfony.php');
import(__DIR__ . '/hosts.yml');

// Git
set('repository', 'git@github.com:King-Of-Paddock/kop-app.git');
set('sub_directory', 'api');
set('branch', 'main');
set('default_stage', 'staging');

// SSH
set('ssh_type', 'native');
set('ssh_multiplexing', false);

// Composer: PHAR installed on the server
set('bin/composer', '{{bin/php}} {{release_path}}/composer.phar');
set('composer_options', '--no-dev --no-scripts --optimize-autoloader --no-interaction --prefer-dist');

// Shared files & dirs (same as before)
add('shared_files', ['config/parameters.yaml']);
add('shared_dirs', ['public/images', 'public/media', 'public/uploads', 'config/jwt']);
add('writable_dirs', ['public/images', 'public/media', 'public/uploads', 'config/jwt', 'var']);

// Task: Install Composer PHAR
task('deploy:install_composer', function () {
    run("cd {{release_path}} && curl -sS https://getcomposer.org/installer | {{bin/php}}");
})->desc('downloading composer');

// Task: Upload pre-built assets from CI
task('upload:assets', function () {
    upload('public/assets/', '{{release_path}}/public/assets/');
})->desc('uploading assets');

// Task: Symfony assets:install
task('deploy:assets:install', function () {
    run('cd {{release_path}} && {{bin/console}} assets:install --env=prod');
});

// Task: Fix var/ permissions
task('chmod:var', function () {
    run('chmod -R 777 {{release_path}}/var');
});

// Hooks (same order as original)
before('deploy:vendors', 'deploy:install_composer');
before('deploy:symlink', 'database:migrate');
before('deploy:symlink', 'upload:assets');
after('upload:assets', 'deploy:assets:install');
after('deploy:assets:install', 'chmod:var');
after('deploy:failed', 'deploy:unlock');
