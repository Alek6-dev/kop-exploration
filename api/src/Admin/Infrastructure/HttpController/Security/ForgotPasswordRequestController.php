<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Security;

use App\Admin\Application\Dto\ForgotPasswordRequestDto;
use App\Admin\Application\Handler\ResetPasswordHandler;
use App\Admin\Domain\Model\UserAdminInterface;
use App\Admin\Domain\Repository\UserAdminRepositoryInterface;
use App\Admin\Infrastructure\Form\ForgotPasswordRequestType;
use App\Shared\Domain\Model\Behaviors\UserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Symfony\Component\Translation\t;

#[Route('/admin/mot-de-passe-oublie', name: 'admin_forgot_password_request')]
final class ForgotPasswordRequestController extends AbstractController
{
    public function __construct(
        protected readonly TranslatorInterface $translator,
        protected readonly MailerInterface $mailer,
        protected readonly LoggerInterface $logger,
        protected readonly UserAdminRepositoryInterface $userAdminRepository
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if ($this->isGranted(AuthenticatedVoter::IS_AUTHENTICATED)) {
            return $this->redirectToRoute($this->getFallbackRouteIfAuthenticated());
        }

        $payload = new ForgotPasswordRequestDto();

        $form = $this->createForm(ForgotPasswordRequestType::class, $payload);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var ?UserAdminInterface $user */
                $user = $this->userAdminRepository->findOneBy([
                    'email' => $payload->email,
                ]);

                if ($user) {
                    ResetPasswordHandler::generateResetPasswordToken($user);

                    $email = $this->getEmail($payload, $user);

                    try {
                        $this->mailer->send($email);
                    } catch (TransportExceptionInterface $e) {
                        $this->logger->critical("Mail de réinitialisation mot de passe n'a pas pu être envoyé \"{$payload->email}\" : ".$e);

                        goto response;
                    }

                    $this->userAdminRepository->update($user);

                    $this->logger->info("Mail de réinitialisation mot de passe bien envoyé pour \"{$payload->email}\".");
                }

                $this->addFlash('success', t('forgot_password_request.form.message.success', ['%email%' => $payload->email], 'security'));

                return $this->redirectToRoute($request->attributes->get('_route'), status: Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', t('form.error.general', domain: 'common'));
        }

        response:
        return $this->render($this->getResponseTemplate(), [
            'form' => $form,
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'mailer' => MailerInterface::class,
            'translator' => TranslatorInterface::class,
            new SubscribedService('mailer.logger', LoggerInterface::class, attributes: new Target('mailerLogger')),
            'user_admin_repository' => UserAdminRepositoryInterface::class,
        ]);
    }

    protected function getEmail(ForgotPasswordRequestDto $payload, UserInterface $user): Email
    {
        return (new TemplatedEmail())
            ->to(new Address($payload->email))
            ->subject($this->translator->trans('forgot_password_request.email.subject', domain: 'security'))
            ->htmlTemplate('Admin/Security/ForgotPassword/Request/email.html.twig')
            ->context([
                'name' => (string) $user,
                'url' => $this->generateUrl('admin_forgot_password_action', [
                    'token' => $user->getResetPasswordToken(),
                ], UrlGeneratorInterface::ABSOLUTE_URL),
            ])
        ;
    }

    protected function getFallbackRouteIfAuthenticated(): string
    {
        return 'admin_dashboard';
    }

    protected function getResponseTemplate(): string
    {
        return 'Admin/Security/ForgotPassword/Request/index.html.twig';
    }
}
