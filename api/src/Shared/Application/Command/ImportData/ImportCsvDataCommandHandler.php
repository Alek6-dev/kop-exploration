<?php

declare(strict_types=1);

namespace App\Shared\Application\Command\ImportData;

use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class ImportCsvDataCommandHandler
{
    public function __invoke(ImportCsvDataCommand $command): void
    {
        $callableFunction = $command->functionOnLine;
        if (($handle = fopen($command->fileToImport->getPathname(), 'r')) !== false) {
            // Read and process the lines.
            $firstLine = true;
            while (($data = fgetcsv($handle)) !== false) {
                // Skip the first line if the file includes a header
                if ($command->hasHeader && $firstLine) {
                    $firstLine = false;
                    continue;
                }
                $callableFunction($data);
            }
            fclose($handle);
        }
    }
}
