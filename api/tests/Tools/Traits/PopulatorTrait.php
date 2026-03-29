<?php
declare(strict_types=1);

namespace App\Tests\Tools\Traits;

trait PopulatorTrait
{
    use CommandRunnerTrait;

    protected static function populate()
    {
        static::runCommand('app:es:populate:all', ['-e' =>'test']);
    }
}
