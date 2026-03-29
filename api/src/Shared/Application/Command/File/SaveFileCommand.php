<?php

declare(strict_types=1);

namespace App\Shared\Application\Command\File;

use App\Shared\Application\Command\CommandInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @implements CommandInterface<self>
 */
class SaveFileCommand implements CommandInterface
{
    public function __construct(
        public File $fileToSave,
        public string $path,
        public string $fileName,
    ) {
    }
}
