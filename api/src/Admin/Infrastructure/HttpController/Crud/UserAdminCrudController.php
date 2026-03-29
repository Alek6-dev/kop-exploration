<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\Admin\Domain\Repository\UserAdminRepositoryInterface;
use App\Admin\Infrastructure\Doctrine\Entity\UserAdmin;
use App\Shared\Domain\Enum\User\RoleEnum;
use App\Shared\Domain\Enum\User\StatusEnum;
use Doctrine\Common\Collections\Criteria;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Symfony\Component\Translation\t;

final class UserAdminCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('firstName', t('dashboard.menu.admin.crud.item.field.first_name.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;
        yield TextField::new('lastName', t('dashboard.menu.admin.crud.item.field.last_name.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;
        yield EmailField::new('email', t('dashboard.menu.admin.crud.item.field.email.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
                new Assert\Email(),
            ])
            ->setRequired(true)
        ;

        yield ChoiceField::new('mainRole', t('dashboard.menu.admin.crud.item.field.role.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
            ->setChoices(RoleEnum::cases())
            ->setTemplatePath('Admin/Crud/Field/role.field.twig')
        ;

        yield ChoiceField::new('status', t('dashboard.menu.admin.crud.item.field.status.label', domain: 'admin'))
            ->onlyOnIndex()
            ->setChoices(StatusEnum::cases())
            ->setTemplatePath('Admin/Crud/Field/status.field.twig')
            ->setRequired(true)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, t('dashboard.menu.admin.crud.index.title', domain: 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, static fn (UserAdmin $u) => t('dashboard.menu.admin.crud.detail.title', ['%admin%' => $u->getEmail()], 'admin'))
            ->setDefaultSort(['createdAt' => Criteria::DESC])
            ->setSearchFields(['firstName', 'lastName', 'email'])
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
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $statusFilter = ChoiceFilter::new('status', t('dashboard.menu.admin.crud.filter.status.label', domain: 'admin'))
            ->setChoices(StatusEnum::visitorFilters($this->translator))
        ;

        return $filters
            ->add($statusFilter)
        ;
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'user_admin_repository' => UserAdminRepositoryInterface::class,
        ]);
    }

    public static function getEntityFqcn(): string
    {
        return UserAdmin::class;
    }
}
