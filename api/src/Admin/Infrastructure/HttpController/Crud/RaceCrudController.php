<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\Race\Domain\Repository\RaceRepositoryInterface;
use App\Race\Infrastructure\Doctrine\Entity\Race;
use App\Shared\Domain\Enum\User\RoleEnum;
use Doctrine\Common\Collections\Criteria;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\Translation\t;

final class RaceCrudController extends AbstractCrudController
{
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', t('dashboard.menu.race.crud.item.field.name.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;
        yield ChoiceField::new('country', t('dashboard.menu.race.crud.item.field.country.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setFormTypeOptions([
                'attr' => ['style' => 'background-color: red'],
            ])
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, t('dashboard.menu.race.crud.index.title', domain: 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, static fn (Race $race) => t('dashboard.menu.race.crud.detail.title', ['%race%' => $race->getName()], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, static fn (Race $race) => t('dashboard.menu.race.crud.edit.title', ['%race%' => $race->getName()], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, t('dashboard.menu.race.crud.new.title', domain: 'admin'))

            ->setDefaultSort(['createdAt' => Criteria::DESC])
            ->setSearchFields(['name', 'country'])
            ->setEntityPermission(RoleEnum::ROLE_SUPER_ADMIN->value)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'race_repository' => RaceRepositoryInterface::class,
        ]);
    }

    public static function getEntityFqcn(): string
    {
        return Race::class;
    }
}
