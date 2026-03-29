<?php

declare(strict_types=1);

namespace App\Shared\Application\Command\Token;

use App\Shared\Application\Command\AsCommandHandler;
use Hidehalo\Nanoid\Client;

#[AsCommandHandler]
final readonly class GenerateUniqueTokenCommandHandler
{
    public function __invoke(GenerateUniqueTokenCommand $command): string
    {
        $client = new Client();
        while (true) {
            $tmpToken = $client->generateId($command->length, Client::MODE_DYNAMIC);
            if (!\in_array($tmpToken, $command->forbiddenToken)) {
                return $tmpToken;
            }
        }
    }
}
