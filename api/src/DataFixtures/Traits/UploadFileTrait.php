<?php

declare(strict_types=1);

namespace App\DataFixtures\Traits;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

trait UploadFileTrait
{
    protected readonly string $projectDir;

    private function getUploadedFile(string $relativePathFile, string $prefixName): UploadedFile
    {
        $basePath = $this->projectDir.$relativePathFile;

        $targetFileName = sprintf('%s-%s.png', $prefixName, Uuid::v4());
        $targetFile = "$basePath/$targetFileName";

        (new Filesystem())->copy("$basePath/placeholder.png", $targetFile);

        return new UploadedFile($targetFile, $targetFileName, test: true);
    }
}
