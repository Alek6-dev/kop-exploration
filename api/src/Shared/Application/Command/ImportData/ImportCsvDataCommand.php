<?php

declare(strict_types=1);

namespace App\Shared\Application\Command\ImportData;

use App\Shared\Application\Command\CommandInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @implements CommandInterface<self>
 */
class ImportCsvDataCommand implements CommandInterface
{
    public function __construct(
        public File $fileToImport,
        public bool $hasHeader,
        public $functionOnLine,
    ) {
    }
}
