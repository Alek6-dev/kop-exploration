<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Domain\Repository\DriverRepositoryInterface;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\Shared\Domain\Enum\User\RoleEnum;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Domain\Repository\TeamRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\Translation\t;

final class DriverCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly DriverRepositoryInterface $driverRepository,
        private readonly TeamRepositoryInterface $teamRepository,
    ) {
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('firstName', t('dashboard.menu.driver.crud.item.field.first_name.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;
        yield TextField::new('lastName', t('dashboard.menu.driver.crud.item.field.last_name.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;

        yield IntegerField::new('minValue', t('dashboard.menu.driver.crud.item.field.min_value.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\PositiveOrZero(),
            ])
            ->setRequired(true)
        ;

        /** @var ?Uuidable $entity */
        $entity = $this->getContext()->getEntity()->getInstance();

        yield ImageField::new('image', t('dashboard.menu.driver.crud.item.field.image.label', domain: 'admin'))
            ->hideOnIndex()
            ->setBasePath('uploads/images/driver/')
            ->setUploadDir('public/uploads/images/driver/')
            ->setUploadedFileNamePattern(sprintf('driver_%s.png', $entity ? $entity->getUuid() : Uuid::v4()))
            ->setFormTypeOption('allow_delete', false)
            ->setFormTypeOption('upload_delete', static function (File $file) {})
            ->setRequired(Crud::PAGE_NEW === $pageName)
        ;

        yield ChoiceField::new('isReplacement', t('dashboard.menu.driver.crud.item.field.is_replacement.label', domain: 'admin'))
            ->setChoices([
                'Non' => 0,
                'Oui' => 1,
            ])
            ->setRequired(true)
        ;

        yield DateField::new('replacementDateStart', t('dashboard.menu.driver.crud.item.field.replacement_date_start.label', domain: 'admin'))
            ->hideOnIndex()
        ;
        yield DateField::new('replacementDateEnd', t('dashboard.menu.driver.crud.item.field.replacement_date_end.label', domain: 'admin'))
            ->hideOnIndex()
        ;

        yield ChoiceField::new('replacedPermanently', t('dashboard.menu.driver.crud.item.field.replaced_permanently.label', domain: 'admin'))
            ->setChoices([
                'Non' => 0,
                'Oui' => 1,
            ])
            ->setRequired(true)
        ;

        yield ChoiceField::new('replacedBy', t('dashboard.menu.driver.crud.item.field.replaced_by.label', domain: 'admin'))
            ->setFormTypeOption('choice_label', function (int|DriverInterface|string $choice): string {
                return (string) $choice;
            })
            ->setFormTypeOption('choice_value', static function (int|DriverInterface|string|null $value): ?string {
                if (null === $value) {
                    return null;
                }

                return (string) $value;
            })
            ->setChoices($this->driverRepository->withIsReplacement(true)->getResult())
            ->hideOnIndex()
            ->hideOnDetail()
        ;

        yield AssociationField::new('replacedBy', t('dashboard.menu.driver.crud.item.field.replaced_by.label', domain: 'admin'))
            ->setRequired(true)
            ->hideOnForm();

        yield ChoiceField::new('team', t('dashboard.menu.driver.crud.item.field.team.label', domain: 'admin'))
            ->setFormTypeOption('choice_label', function (int|TeamInterface|string $choice): string {
                return $choice->getName();
            })
            ->setFormTypeOption('choice_value', static function (int|TeamInterface|string|null $value): ?string {
                if (null === $value) {
                    return null;
                }

                return (string) ($value instanceof TeamInterface ? $value->getName() : $value);
            })
            ->setChoices($this->teamRepository->getAll())
            ->hideOnIndex()
            ->hideOnDetail()
            ->setRequired(true);

        yield AssociationField::new('team', t('dashboard.menu.driver.crud.item.field.team.label', domain: 'admin'))
            ->setRequired(true)
            ->hideOnForm();
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, t('dashboard.menu.driver.crud.index.title', domain: 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, static fn (Driver $driver) => t('dashboard.menu.driver.crud.detail.title', ['%driver%' => $driver->getFirstName().' '.$driver->getLastName()], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, static fn (Driver $driver) => t('dashboard.menu.driver.crud.edit.title', ['%driver%' => $driver->getFirstName().' '.$driver->getLastName()], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, t('dashboard.menu.driver.crud.new.title', domain: 'admin'))
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
            'driver_repository' => DriverRepositoryInterface::class,
        ]);
    }

    public static function getEntityFqcn(): string
    {
        return Driver::class;
    }
}
