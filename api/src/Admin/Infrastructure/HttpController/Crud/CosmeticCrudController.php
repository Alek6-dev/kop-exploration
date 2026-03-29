<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\Cosmetic\Domain\Enum\TypeCosmeticEnum;
use App\Cosmetic\Domain\Repository\CosmeticRepositoryInterface;
use App\Cosmetic\Infrastructure\Doctrine\Entity\Cosmetic;
use App\Shared\Domain\Enum\User\RoleEnum;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use Doctrine\Common\Collections\Criteria;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\Translation\t;

final class CosmeticCrudController extends AbstractCrudController
{
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', t('dashboard.menu.cosmetic.crud.item.field.name.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;
        yield TextareaField::new('description', t('dashboard.menu.cosmetic.crud.item.field.description.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;

        yield IntegerField::new('price', t('dashboard.menu.cosmetic.crud.item.field.price.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\PositiveOrZero(),
            ])
            ->setRequired(true)
        ;

        yield ChoiceField::new('type', t('dashboard.menu.cosmetic.crud.item.field.type.label', domain: 'admin'))
            ->setChoices(TypeCosmeticEnum::cases())
            ->setRequired(true)
        ;

        yield ColorField::new('color', t('dashboard.menu.cosmetic.crud.item.field.color.label', domain: 'admin'))
            ->showValue()
            ->setRequired(true)
        ;

        /** @var ?Uuidable $entity */
        $entity = $this->getContext()->getEntity()->getInstance();

        yield ImageField::new('image1', t('dashboard.menu.cosmetic.crud.item.field.image1.label', domain: 'admin'))
            ->hideOnIndex()
            ->setBasePath('uploads/images/cosmetic/')
            ->setUploadDir('public/uploads/images/cosmetic/')
            ->setUploadedFileNamePattern(sprintf('cosmetic_%s_1.png', $entity ? $entity->getUuid() : Uuid::v4()))
            ->setFormTypeOption('allow_delete', false)
            ->setFormTypeOption('upload_delete', static function (File $file) {})
            ->setRequired(Crud::PAGE_NEW === $pageName)
        ;

        yield ImageField::new('image2', t('dashboard.menu.cosmetic.crud.item.field.image2.label', domain: 'admin'))
            ->hideOnIndex()
            ->setBasePath('uploads/images/cosmetic/')
            ->setUploadDir('public/uploads/images/cosmetic/')
            ->setUploadedFileNamePattern(sprintf('cosmetic_%s_2.png', $entity ? $entity->getUuid() : Uuid::v4()))
            ->setFormTypeOption('allow_delete', false)
            ->setFormTypeOption('upload_delete', static function (File $file) {})
            ->setRequired(Crud::PAGE_NEW === $pageName)
        ;

        yield ChoiceField::new('isDefault', t('dashboard.menu.cosmetic.crud.item.field.is_default.label', domain: 'admin'))
            ->setChoices([
                'Non' => 0,
                'Oui' => 1,
            ])
            ->setRequired(true)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, t('dashboard.menu.cosmetic.crud.index.title', domain: 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, static fn (Cosmetic $cosmetic) => t('dashboard.menu.cosmetic.crud.detail.title', ['%cosmetic%' => $cosmetic->getName()], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, static fn (Cosmetic $cosmetic) => t('dashboard.menu.cosmetic.crud.edit.title', ['%cosmetic%' => $cosmetic->getName()], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, t('dashboard.menu.cosmetic.crud.new.title', domain: 'admin'))
            ->setDefaultSort(['createdAt' => Criteria::DESC])
            ->setSearchFields(['firstName', 'lastName', 'email'])
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
            'cosmetic_repository' => CosmeticRepositoryInterface::class,
        ]);
    }

    public static function getEntityFqcn(): string
    {
        return Cosmetic::class;
    }
}
