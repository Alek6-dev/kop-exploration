<?php

declare(strict_types=1);

namespace App\Shared\Application\Command\File;

use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class SaveFileCommandHandler
{
    public function __invoke(SaveFileCommand $command): void
    {
        $command->fileToSave->move(
            $command->path,
            $command->fileName
        );
    }
}
