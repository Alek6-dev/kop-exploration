<?php

declare(strict_types=1);

namespace App\Cosmetic\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Cosmetic\Application\Command\BuyCosmetic\BuyCosmeticCommand;
use App\Cosmetic\Application\Query\Get\GetCosmeticQuery;
use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Cosmetic\Infrastructure\ApiPlatform\Resource\CosmeticResource;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<false, CosmeticResource>
 */
final readonly class BuyCosmeticProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): CosmeticResource
    {
        $uuid = $uriVariables['uuid'];

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();

        /** @var CosmeticInterface $cosmetic */
        $cosmetic = $this->queryBus->ask(new GetCosmeticQuery(
            $uuid,
        ));

        $this->commandBus->dispatch(new BuyCosmeticCommand(
            $user,
            $cosmetic,
        ));

        return CosmeticResource::fromModel($cosmetic, $user);
    }
}
