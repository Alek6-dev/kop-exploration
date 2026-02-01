<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\User\Application\Command\Valid\ValidUserCommand;
use App\User\Application\Dto\TokenDto;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

/**
 * @implements ProcessorInterface<null, TokenDto>
 */
final readonly class ValidAccountProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private JWTTokenManagerInterface $tokenManager,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): TokenDto
    {
        /** @var string $token */
        $token = $uriVariables['token'];

        /** @var UserVisitorInterface $model */
        $model = $this->commandBus->dispatch(new ValidUserCommand(
            $token,
        ));

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return new TokenDto($this->tokenManager->create($model));
    }
}
