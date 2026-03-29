<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\User\Application\Command\ResetPassword\ResetPasswordUserCommand;
use App\User\Application\Dto\ResetPasswordDto;
use App\User\Application\Dto\TokenDto;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @implements ProcessorInterface<ResetPasswordDto, TokenDto>
 */
final readonly class ResetPasswordUserProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ValidatorInterface $validator,
        private JWTTokenManagerInterface $tokenManager,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): TokenDto
    {
        /** @var string $token */
        $token = $uriVariables['token'];

        $errors = $this->validator->validate($data);
        if (\count($errors) > 0) {
            /* @phpstan-ignore-next-line Fixed in Symfony 7.0 */
            throw UserVisitorException::invalidData((string) $errors);
        }

        /** @var UserVisitorInterface $model */
        $model = $this->commandBus->dispatch(new ResetPasswordUserCommand(
            $token,
            $data->password
        ));

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return new TokenDto($this->tokenManager->create($model));
    }
}
