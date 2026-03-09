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
set('composer_options', function () {
    $options = '--no-scripts --optimize-autoloader --no-interaction --prefer-dist';
    if (get('stage') === 'prod') {
        $options = '--no-dev ' . $options;
    }
    return $options;
});

// Shared files & dirs
add('shared_files', ['.env.local', 'config/parameters.yaml']);
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
// staging-kop:www-data share the same group, setgid ensures new files inherit www-data group
task('chmod:var', function () {
    // Release var/ — group writable + setgid on dirs
    run('chmod -R g+rwX {{release_path}}/var');
    run('find {{release_path}}/var -type d -exec chmod g+s {} +');
    // Shared var/ (logs) — same, ignore errors on files we don't own
    run('chmod -R g+rwX {{deploy_path}}/shared/var 2>/dev/null || true');
    run('find {{deploy_path}}/shared/var -type d -exec chmod g+s {} + 2>/dev/null || true');
});

// Hooks
before('deploy:vendors', 'deploy:install_composer');
before('deploy:symlink', 'database:migrate');
before('deploy:symlink', 'upload:assets');
after('upload:assets', 'deploy:assets:install');
before('deploy:symlink', 'chmod:var');
after('deploy:failed', 'deploy:unlock');
