<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/connexion', name: 'admin_login')]
final class LoginController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted(AuthenticatedVoter::IS_AUTHENTICATED)) {
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('Admin/Security/Login/index.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername(),
            'page_title' => $this->translator->trans('login.title', domain: 'security'),
            'csrf_token_intention' => 'authenticate',
            'target_path' => $this->generateUrl('admin_dashboard'),
            'forgot_password_enabled' => true,
            'forgot_password_path' => $this->generateUrl('admin_forgot_password_request'),
            'remember_me_enabled' => true,
        ]);
    }
}
