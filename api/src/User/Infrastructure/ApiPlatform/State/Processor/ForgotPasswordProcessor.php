<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\Email\SendEmailCommand;
use App\Shared\Domain\Exception\EmailException;
use App\User\Application\Dto\ForgotPasswordDto;
use App\User\Application\ForgotPassword\ForgotPasswordCommand;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;

/**
 * @implements ProcessorInterface<UserVisitorInterface, bool>
 */
final readonly class ForgotPasswordProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ValidatorInterface $validator,
        private TranslatorInterface $translator,
        private EntityManagerInterface $entityManager,
        private string $forgotPasswordFrontUrl,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): bool
    {
        Assert::isInstanceOf($data, ForgotPasswordDto::class);

        $errors = $this->validator->validate($data);
        if (\count($errors) > 0) {
            /* @phpstan-ignore-next-line Fixed in Symfony 7.0 */
            throw UserVisitorException::invalidData((string) $errors);
        }

        try {
            /** @var UserVisitorInterface $model */
            $model = $this->commandBus->dispatch(new ForgotPasswordCommand($data->email));

            if (!$model->getResetPasswordToken()) {
                throw UserVisitorException::resetPasswordTokenNotFound($model->getUuid());
            }

            $this->entityManager->persist($model);
            $this->entityManager->flush();

            $emailCommand = new SendEmailCommand(
                $model->getEmail(),
                $this->translator->trans('forgot_password_request.email.subject', domain: 'security'),
                'security/forgot_password/email.html.twig',
                [
                    'name' => (string) $model,
                    'url' => str_replace('{uuid}', $model->getResetPasswordToken(), $this->forgotPasswordFrontUrl),
                ]
            );

            $this->commandBus->dispatch($emailCommand);
        } catch (\Throwable) {
            throw EmailException::unknownError();
        }

        return true;
    }
}
