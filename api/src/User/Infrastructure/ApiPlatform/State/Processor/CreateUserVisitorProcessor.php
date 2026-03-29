<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Cosmetic\Application\Command\AddDefaultCosmetics\AddDefaultCosmeticsCommand;
use App\Parameter\Application\Query\Get\GetParameterQuery;
use App\Parameter\Domain\Model\ParameterInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\Email\SendEmailCommand;
use App\Shared\Application\Command\File\SaveFileCommand;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Exception\EmailException;
use App\User\Application\Command\Confirm\ConfirmUserCommand;
use App\User\Application\Command\Create\CreateUserVisitorCommand;
use App\User\Application\Dto\CreateUserDto;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Infrastructure\ApiPlatform\Resource\UserResource;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;

/**
 * @implements ProcessorInterface<CreateUserDto, UserResource>
 */
final readonly class CreateUserVisitorProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private ValidatorInterface $validator,
        private TranslatorInterface $translator,
        private EntityManagerInterface $entityManager,
        private string $projectDir,
        private string $userDirectoryImage,
        private string $validRegistrationFrontUrl,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): UserResource
    {
        Assert::isInstanceOf($data, CreateUserDto::class);

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
                $fileName
            );

            $this->commandBus->dispatch($fileCommand);
        }

        try {
            /* @var UserVisitorInterface $model */
            $model = $this->commandBus->dispatch(new CreateUserVisitorCommand(
                $data->pseudo,
                $data->email,
                $data->password,
                $fileName,
            ));

            $model = $this->commandBus->dispatch(new AddDefaultCosmeticsCommand(
                $model
            ));

            $this->entityManager->persist($model);
            $this->entityManager->flush();

            /** @var ParameterInterface $parameter */
            $parameter = $this->queryBus->ask(new GetParameterQuery(
                'user_confirmation_by_admin'
            ));
            if (!$parameter->getValue()) {
                /** @var UserVisitorInterface $model */
                $model = $this->commandBus->dispatch(new ConfirmUserCommand(
                    $model->getUuid(),
                ));

                if (!$model->getEmailValidationToken()) {
                    UserVisitorException::emailValidationTokenNotFound($model->getUuid());
                }

                $emailCommand = new SendEmailCommand(
                    $model->getEmail(),
                    $this->translator->trans('registration.email.subject', domain: 'security'),
                    'security/registration/email.html.twig',
                    [
                        'name' => (string) $model,
                        'url' => str_replace('{uuid}', $model->getEmailValidationToken(), $this->validRegistrationFrontUrl),
                    ]);

                $this->commandBus->dispatch($emailCommand);
            }
        } catch (UniqueConstraintViolationException $exception) {
            if (str_contains($exception->getMessage(), UserVisitor::EMAIL_UNIQUE_IDX)) {
                throw UserVisitorException::emailAlreadyUsed($data->email);
            } elseif (str_contains($exception->getMessage(), UserVisitor::PSEUDO_UNIQUE_IDX)) {
                throw UserVisitorException::pseudoAlreadyUsed($data->pseudo);
            }
        } catch (\Throwable $e) {
            throw EmailException::unknownError();
        }

        return UserResource::fromModel($model);
    }
}
