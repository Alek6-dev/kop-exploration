<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\Shared\Domain\Enum\User\RoleEnum;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use App\Team\Domain\Repository\TeamRepositoryInterface;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\Common\Collections\Criteria;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\Translation\t;

final class TeamCrudController extends AbstractCrudController
{
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', t('dashboard.menu.team.crud.item.field.name.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;

        /** @var ?Uuidable $entity */
        $entity = $this->getContext()->getEntity()->getInstance();

        yield ImageField::new('image', t('dashboard.menu.team.crud.item.field.image.label', domain: 'admin'))
            ->hideOnIndex()
            ->setBasePath('uploads/images/team/')
            ->setUploadDir('public/uploads/images/team/')
            ->setUploadedFileNamePattern(sprintf('team_%s.png', $entity ? $entity->getUuid() : Uuid::v4()))
            ->setFormTypeOption('allow_delete', false)
            ->setFormTypeOption('upload_delete', static function (File $file) {})
            ->setRequired(Crud::PAGE_NEW === $pageName)
        ;

        yield IntegerField::new('minValue', t('dashboard.menu.team.crud.item.field.min_value.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\PositiveOrZero(),
            ])
            ->setRequired(true)
        ;

        yield ColorField::new('color', t('dashboard.menu.cosmetic.crud.item.field.color.label', domain: 'admin'))
            ->showValue()
            ->setRequired(true)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, t('dashboard.menu.team.crud.index.title', domain: 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, static fn (Team $team) => t('dashboard.menu.team.crud.detail.title', ['%team%' => $team->getName()], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, static fn (Team $team) => t('dashboard.menu.team.crud.edit.title', ['%team%' => $team->getName()], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, t('dashboard.menu.team.crud.new.title', domain: 'admin'))
            ->setDefaultSort(['createdAt' => Criteria::DESC])
            ->setSearchFields(['name'])
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
            'team_repository' => TeamRepositoryInterface::class,
        ]);
    }

    public static function getEntityFqcn(): string
    {
        return Team::class;
    }
}
