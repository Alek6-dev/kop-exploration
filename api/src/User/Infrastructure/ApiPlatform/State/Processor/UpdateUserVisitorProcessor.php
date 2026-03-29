<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\File\SaveFileCommand;
use App\User\Application\Command\Update\UpdateUserVisitorCommand;
use App\User\Application\Dto\TokenDto;
use App\User\Application\Dto\UpdateUserDto;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

/**
 * @implements ProcessorInterface<UpdateUserDto, TokenDto>
 */
final readonly class UpdateUserVisitorProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
        private Security $security,
        private JWTTokenManagerInterface $tokenManager,
        private string $projectDir,
        private string $userDirectoryImage,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): TokenDto
    {
        $uuid = $uriVariables['uuid'];

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();

        if ($uuid !== $user->getUuid()) {
            UserVisitorException::notAllowedToUpdateInformation();
        }

        Assert::isInstanceOf($data, UpdateUserDto::class);

        $errors = $this->validator->validate($data);
        if (\count($errors) > 0) {
            /* @phpstan-ignore-next-line Fixed in Symfony 7.0 */
            throw UserVisitorException::invalidData((string) $errors);
        }

        $fileName = null;
        if ($data->imageFile) {
            $fileName = new UuidV4().'.'.$data->imageFile->guessExtension();
            $fileCommand = new SaveFileCommand(
                $data->imageFile,
                $this->projectDir.'/public'.$this->userDirectoryImage,
                $this->userDirectoryImage.$fileName
            );

            $this->commandBus->dispatch($fileCommand);
        }

        /** @var string $uuid */
        $uuid = $uriVariables['uuid'];

        /** @var UserVisitorInterface $model */
        $model = $this->commandBus->dispatch(new UpdateUserVisitorCommand(
            $uuid,
            $data->email,
            $data->pseudo,
            $data->password,
            $fileName
        ));

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return new TokenDto($this->tokenManager->create($model));
    }
}
