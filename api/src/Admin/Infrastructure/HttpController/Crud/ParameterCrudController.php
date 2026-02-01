<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\Parameter\Domain\Enum\TypeEnum;
use App\Parameter\Domain\Model\ParameterInterface;
use App\Parameter\Domain\Repository\ParameterRepositoryInterface;
use App\Parameter\Infrastructure\Doctrine\Entity\Parameter;
use App\Shared\Domain\Enum\User\RoleEnum;
use Doctrine\Common\Collections\Criteria;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use function Symfony\Component\Translation\t;

final class ParameterCrudController extends AbstractCrudController
{
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('label', t('dashboard.menu.parameter.crud.item.field.label.label', domain: 'admin'))
            ->setFormTypeOption('disabled', 'disabled')
        ;

        /** @var ?ParameterInterface $currentParameter */
        $currentParameter = Crud::PAGE_INDEX !== $this->getContext()->getCrud()->getCurrentPage() ? $this->getContext()->getEntity()->getInstance() : null;

        match ($currentParameter?->getType()) {
            TypeEnum::BOOL => yield ChoiceField::new('value', t('dashboard.menu.parameter.crud.item.field.value.label', domain: 'admin'))
                ->setChoices([
                    'Non' => 0,
                    'Oui' => 1,
                ]),
            TypeEnum::NUMBER => yield NumberField::new('value', t('dashboard.menu.parameter.crud.item.field.value.label', domain: 'admin')),
            default => yield TextField::new('value', t('dashboard.menu.parameter.crud.item.field.value.label', domain: 'admin')),
        };
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, t('dashboard.menu.parameter.crud.index.title', domain: 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, static fn (ParameterInterface $parameter) => t('dashboard.menu.parameter.crud.detail.title', ['%label%' => $parameter->getLabel()], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, static fn (ParameterInterface $parameter) => t('dashboard.menu.parameter.crud.edit.title', ['%label%' => $parameter->getLabel()], 'admin'))
            ->setDefaultSort(['createdAt' => Criteria::DESC])
            ->setSearchFields(['label'])
            ->setEntityPermission(RoleEnum::ROLE_SUPER_ADMIN->value)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
        ;
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'parameter_repository' => ParameterRepositoryInterface::class,
        ]);
    }

    public static function getEntityFqcn(): string
    {
        return Parameter::class;
    }
}
