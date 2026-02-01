<?php
declare(strict_types=1);

namespace App\Tests\Tools\Traits;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

trait CommandRunnerTrait
{
    use KernelAwareTrait;

    public static function runCommand(string $command, array $options): OutputInterface
    {
        $application = new Application(static::getContainer()->get('kernel'));
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => $command
        ] + $options);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $res = $application->doRun($input, $output);

        if ($res != 0) {
            throw new \Exception($command . ' could not run');
        }

        return $output;
    }
}
