<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\Email\SendEmailCommand;
use App\Shared\Domain\Enum\User\RoleEnum;
use App\Shared\Domain\Enum\User\StatusEnum;
use App\User\Application\Command\Confirm\ConfirmUserCommand;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\Common\Collections\Criteria;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Symfony\Component\Translation\t;

final class UserVisitorCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly CommandBusInterface $commandBus,
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly string $validRegistrationFrontUrl,
    ) {
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('firstName', t('dashboard.menu.user.crud.item.field.first_name.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;

        yield TextField::new('lastName', t('dashboard.menu.user.crud.item.field.last_name.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;

        yield EmailField::new('email', t('dashboard.menu.user.crud.item.field.email.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
                new Assert\Email(),
            ])
            ->setRequired(true)
        ;

        yield TextField::new('pseudo', t('dashboard.menu.user.crud.item.field.pseudo.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
                new Assert\Email(),
            ])
            ->setRequired(true)
        ;

        yield ChoiceField::new('status', t('dashboard.menu.user.crud.item.field.status.label', domain: 'admin'))
            ->setChoices(StatusEnum::cases())
            ->setTemplatePath('Admin/Crud/Field/status.field.twig')
            ->setRequired(true)
        ;

        yield DateTimeField::new('createdAt', t('dashboard.menu.user.crud.item.field.created_at.label', domain: 'admin'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, t('dashboard.menu.user.crud.index.title', domain: 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, static fn (UserVisitor $u) => t('dashboard.menu.user.crud.detail.title', ['%user%' => $u->getPseudo()], 'admin'))
            ->setDefaultSort(['createdAt' => Criteria::DESC])
            ->setSearchFields(['pseudo', 'firstName', 'lastName', 'email'])
            ->setEntityPermission(RoleEnum::ROLE_SUPER_ADMIN->value)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)

            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)

            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            ->add(Crud::PAGE_DETAIL, $this->getConfirmUserAction('confirm_user'))
        ;
    }

    public function getConfirmUserAction(string $name): Action
    {
        return Action::new($name)
            ->linkToCrudAction('confirmUser')
            ->setCssClass('btn btn-primary')
            ->setIcon('fa fa-fw fa-check')
            ->setLabel(t('dashboard.menu.user.crud.button.confirm_user.label', domain: 'admin'))
            ->displayIf(static function (UserVisitorInterface $user): bool {
                return StatusEnum::WAITING_ADMIN_CONFIRMATION === $user->getStatus();
            })
        ;
    }

    public function confirmUser(AdminContext $context): RedirectResponse
    {
        try {
            /** @var UserVisitorInterface $entity */
            $entity = $context->getEntity()->getInstance();

            $entity = $this->commandBus->dispatch(new ConfirmUserCommand(
                $entity->getUuid()
            ));

            $this->commandBus->dispatch(new SendEmailCommand(
                $entity->getEmail(),
                $this->translator->trans('registration.email.subject', domain: 'security'),
                'security/registration/email.html.twig',
                [
                    'name' => (string) $entity,
                    'url' => str_replace('{uuid}', $entity->getEmailValidationToken(), $this->validRegistrationFrontUrl),
                ]
            ));
        } catch (\Exception) {
            $this->addFlash('danger', $this->translator->trans(
                'dashboard.menu.user.crud.error.confirm.label',
                [
                    '%pseudo%' => $entity->getPseudo(),
                ],
                'admin'
            ));
        }

        return $this->redirect($this->adminUrlGenerator
            ->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($entity->getId())
            ->generateUrl());
    }

    public function configureFilters(Filters $filters): Filters
    {
        $statusFilter = ChoiceFilter::new('status', t('dashboard.menu.user.crud.filter.status.label', domain: 'admin'))
            ->setChoices(StatusEnum::visitorFilters($this->translator))
        ;

        return $filters
            ->add($statusFilter)
        ;
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'user_visitor_repository' => UserVisitorRepositoryInterface::class,
        ]);
    }

    public static function getEntityFqcn(): string
    {
        return UserVisitor::class;
    }
}
