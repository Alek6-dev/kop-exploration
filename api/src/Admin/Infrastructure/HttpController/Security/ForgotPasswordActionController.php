<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Security;

use App\Admin\Application\Dto\ForgotPasswordActionDto;
use App\Admin\Application\Handler\ResetPasswordHandler;
use App\Admin\Domain\Model\UserAdminInterface;
use App\Admin\Domain\Repository\UserAdminRepositoryInterface;
use App\Admin\Infrastructure\Form\ForgotPasswordActionType;
use App\Shared\Domain\Model\Behaviors\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Translation\TranslatableMessage;

use function Symfony\Component\Translation\t;

#[Route('/admin/mot-de-passe-oublie/{token}', name: 'admin_forgot_password_action')]
final class ForgotPasswordActionController extends AbstractController
{
    public function __construct(
        protected readonly UserPasswordHasherInterface $userPasswordHasher,
        protected readonly Security $security,
        protected readonly UserAdminRepositoryInterface $userAdminRepository
    ) {
    }

    public function __invoke(Request $request, ?string $token): Response
    {
        if ($this->isGranted(AuthenticatedVoter::IS_AUTHENTICATED)) {
            return $this->redirectToRoute($this->getFallbackRouteIfAuthenticated());
        }

        /** @var ?UserAdminInterface $user */
        $user = $this->userAdminRepository->findOneBy([
            'resetPasswordToken' => $token,
        ]);

        if (!$user) {
            $this->addFlash('danger', t('forgot_password_action.message.user_not_found', domain: 'admin'));

            return $this->redirectToRoute($this->getForgotPasswordRequestRoute());
        }

        $payload = new ForgotPasswordActionDto();

        $form = $this->createForm(ForgotPasswordActionType::class, $payload);
        $form->handleRequest($request);

        $title = $this->getTitle($user);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $password = $this->userPasswordHasher->hashPassword($user, $payload->password);

                $user->setPassword($password);

                $this->userAdminRepository->update($user);

                $this->security->login($user, $this->getAuthenticatorName());
                $this->addSuccessFlash($user);

                return $this->redirectToRoute($this->getSuccessRoute(), status: Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', t('form.error.general', domain: 'common'));

            goto response;
        }

        if (!ResetPasswordHandler::isResetPasswordTokenValid($user)) {
            ResetPasswordHandler::clearResetPasswordTokenRequest($user);

            $this->userAdminRepository->update($user);

            $this->addFlash('danger', t('forgot_password_action.message.user_not_found', domain: 'admin'));

            return $this->redirectToRoute($this->getForgotPasswordRequestRoute());
        }

        response:
        return $this->render($this->getResponseTemplate(), [
            'title' => $title,
            'form' => $form,
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'user_admin_repository' => UserAdminRepositoryInterface::class,
        ]);
    }

    protected function getTitle(UserInterface $user): string|TranslatableMessage
    {
        if ($user->getPassword()) {
            return t('forgot_password_action.title', domain: 'admin');
        }

        return t('forgot_password_create.title', domain: 'admin');
    }

    protected function addSuccessFlash(UserInterface $user): void
    {
        $this->addFlash('success', t('forgot_password_create.message.success', domain: 'admin'));
    }

    protected function getFallbackRouteIfAuthenticated(): string
    {
        return 'admin_dashboard';
    }

    protected function getForgotPasswordRequestRoute(): string
    {
        return 'admin_forgot_password_request';
    }

    protected function getSuccessRoute(): string
    {
        return 'admin_dashboard';
    }

    protected function getResponseTemplate(): string
    {
        return 'Admin/Security/ForgotPassword/Action/index.html.twig';
    }

    protected function getAuthenticatorName(): ?string
    {
        return null;
    }
}
