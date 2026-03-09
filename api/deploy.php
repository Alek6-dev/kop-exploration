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
// Override inherited default ACL (home has rX only for www-data) with rwX
task('chmod:var', function () {
    // Release var/ — staging-kop owns these so setfacl works
    run('setfacl -R -m u:www-data:rwX {{release_path}}/var');
    run('setfacl -dR -m u:www-data:rwX {{release_path}}/var');
    // Shared var/ (logs) — ignore errors on files already owned by www-data
    run('setfacl -R -m u:www-data:rwX {{deploy_path}}/shared/var 2>/dev/null || true');
    run('setfacl -dR -m u:www-data:rwX {{deploy_path}}/shared/var 2>/dev/null || true');
    run('setfacl -R -m u:staging-kop:rwX {{deploy_path}}/shared/var 2>/dev/null || true');
    run('setfacl -dR -m u:staging-kop:rwX {{deploy_path}}/shared/var 2>/dev/null || true');
});

// Task: Fix old releases permissions before cleanup
task('deploy:cleanup:permissions', function () {
    $releases = get('releases_list');
    $keep = get('keep_releases');
    $releasesToRemove = array_slice($releases, $keep);
    foreach ($releasesToRemove as $release) {
        $releasePath = '{{deploy_path}}/releases/' . $release;
        // Give staging-kop full access to files created by www-data at runtime
        run("setfacl -R -m u:staging-kop:rwX $releasePath/var 2>/dev/null || true");
    }
});

// Hooks
before('deploy:vendors', 'deploy:install_composer');
before('deploy:symlink', 'database:migrate');
before('deploy:symlink', 'upload:assets');
after('upload:assets', 'deploy:assets:install');
before('deploy:symlink', 'chmod:var');
before('deploy:cleanup', 'deploy:cleanup:permissions');
after('deploy:failed', 'deploy:unlock');
