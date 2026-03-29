<?php

declare(strict_types=1);

return static function (Symfony\Config\FrameworkConfig $frameworkConfig): void {
    $frameworkConfig->uid()->defaultUuidVersion(7)->timeBasedUuidVersion(7);
};
