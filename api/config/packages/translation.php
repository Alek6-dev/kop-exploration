<?php

declare(strict_types=1);

return static function (Symfony\Config\FrameworkConfig $frameworkConfig): void {
    $frameworkConfig->defaultLocale('%locale%');
    $frameworkConfig->translator()->defaultPath('%kernel.project_dir%/translations')->fallbacks(['en']);
};
