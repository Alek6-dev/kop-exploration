<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\Bonus\Domain\Enum\AttributeEnum;
use App\Bonus\Domain\Enum\BonusTypeEnum;
use App\Bonus\Domain\Enum\OperationEnum;
use App\Bonus\Domain\Enum\SubTargetTypeEnum;
use App\Bonus\Domain\Enum\TargetTypeEnum;
use App\Bonus\Domain\Repository\BonusRepositoryInterface;
use App\Bonus\Infrastructure\Doctrine\Entity\Bonus;
use App\Shared\Domain\Enum\User\RoleEnum;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use Doctrine\Common\Collections\Criteria;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Symfony\Component\Translation\t;

final class BonusCrudController extends AbstractCrudController
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', t('dashboard.menu.bonus.crud.item.field.name.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;
        yield TextareaField::new('description', t('dashboard.menu.bonus.crud.item.field.description.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
            ->hideOnIndex()
        ;

        yield TextareaField::new('example', t('dashboard.menu.bonus.crud.item.field.example.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
            ->hideOnIndex()
        ;

        /** @var ?Uuidable $entity */
        $entity = $this->getContext()?->getEntity()->getInstance();

        yield ImageField::new('icon', t('dashboard.menu.bonus.crud.item.field.icon.label', domain: 'admin'))
            ->hideOnIndex()
            ->setBasePath('uploads/images/bonus/')
            ->setUploadDir('public/uploads/images/bonus/')
            ->setUploadedFileNamePattern(sprintf('bonus_%s_1.png', $entity ? $entity->getUuid() : Uuid::v4()))
            ->setFormTypeOption('allow_delete', false)
            ->setFormTypeOption('upload_delete', static function (File $file) {})
            ->setRequired(Crud::PAGE_NEW === $pageName)
        ;

        yield IntegerField::new('price', t('dashboard.menu.bonus.crud.item.field.price.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\PositiveOrZero(),
            ])
            ->setRequired(true)
        ;

        yield ChoiceField::new('type', t('dashboard.menu.bonus.crud.item.field.type.label', domain: 'admin'))
            ->setChoices(BonusTypeEnum::cases())
            ->formatValue(static fn (BonusTypeEnum $choice) => t('dashboard.menu.bonus.crud.item.field.type.value.'.$choice->value, domain: 'admin'))
            ->setFormTypeOption('choice_label', function (int|BonusTypeEnum|string $choice): string {
                return $this->translator->trans('dashboard.menu.bonus.crud.item.field.type.value.'.$choice->value, domain: 'admin');
            })
            ->setFormTypeOption('choice_value', static function (int|BonusTypeEnum|string|null $value): ?string {
                if (null === $value) {
                    return null;
                }

                return (string) ($value instanceof BonusTypeEnum ? $value->value : $value);
            })
            ->setRequired(true)
        ;
        yield ChoiceField::new('targetType', t('dashboard.menu.bonus.crud.item.field.target_type.label', domain: 'admin'))
            ->setChoices(TargetTypeEnum::cases())
            ->formatValue(static fn (TargetTypeEnum $choice) => t('dashboard.menu.bonus.crud.item.field.target_type.value.'.$choice->value, domain: 'admin'))
            ->setFormTypeOption('choice_label', function (int|TargetTypeEnum|string $choice): string {
                return $this->translator->trans('dashboard.menu.bonus.crud.item.field.target_type.value.'.$choice->value, domain: 'admin');
            })
            ->setFormTypeOption('choice_value', static function (int|TargetTypeEnum|string|null $value): ?string {
                if (null === $value) {
                    return null;
                }

                return (string) ($value instanceof TargetTypeEnum ? $value->value : $value);
            })

            ->setRequired(true)
        ;

        yield ChoiceField::new('subTargetType', t('dashboard.menu.bonus.crud.item.field.sub_target_type.label', domain: 'admin'))
            ->setChoices(SubTargetTypeEnum::cases())
            ->formatValue(static fn (SubTargetTypeEnum $choice) => t('dashboard.menu.bonus.crud.item.field.sub_target_type.value.'.$choice->value, domain: 'admin'))
            ->setFormTypeOption('choice_label', function (int|SubTargetTypeEnum|string $choice): string {
                return $this->translator->trans('dashboard.menu.bonus.crud.item.field.sub_target_type.value.'.$choice->value, domain: 'admin');
            })
            ->setFormTypeOption('choice_value', static function (int|SubTargetTypeEnum|string|null $value): ?string {
                if (null === $value) {
                    return null;
                }

                return (string) ($value instanceof SubTargetTypeEnum ? $value->value : $value);
            })
            ->setRequired(true)
        ;

        yield ChoiceField::new('attribute', t('dashboard.menu.bonus.crud.item.field.attribute.label', domain: 'admin'))
            ->setChoices(AttributeEnum::cases())
            ->formatValue(static fn (AttributeEnum $choice) => t('dashboard.menu.bonus.crud.item.field.attribute.value.'.$choice->value, domain: 'admin'))
            ->setFormTypeOption('choice_label', function (int|AttributeEnum|string $choice): string {
                return $this->translator->trans('dashboard.menu.bonus.crud.item.field.attribute.value.'.$choice->value, domain: 'admin');
            })
            ->setFormTypeOption('choice_value', static function (int|AttributeEnum|string|null $value): ?string {
                if (null === $value) {
                    return null;
                }

                return (string) ($value instanceof AttributeEnum ? $value->value : $value);
            })
        ;

        yield ChoiceField::new('operation', t('dashboard.menu.bonus.crud.item.field.operation.label', domain: 'admin'))
            ->setChoices(OperationEnum::cases())
            ->formatValue(static fn (OperationEnum $choice) => t('dashboard.menu.bonus.crud.item.field.operation.value.'.strtolower($choice->name), domain: 'admin'))
            ->setFormTypeOption('choice_label', function (int|OperationEnum|string $choice): string {
                return $this->translator->trans('dashboard.menu.bonus.crud.item.field.operation.value.'.strtolower($choice->name), domain: 'admin');
            })
            ->setFormTypeOption('choice_value', static function (int|OperationEnum|string|null $value): ?string {
                if (null === $value) {
                    return null;
                }

                return (string) ($value instanceof OperationEnum ? $value->value : $value);
            })
        ;

        yield IntegerField::new('value', t('dashboard.menu.bonus.crud.item.field.value.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\PositiveOrZero(),
            ])
        ;

        //  TODO: Available in v2
        //        yield ChoiceField::new('isJoker', t('dashboard.menu.driver.crud.item.field.is_joker.label', domain: 'admin'))
        //            ->setChoices([
        //                'Non' => 0,
        //                'Oui' => 1,
        //            ])
        //            ->setRequired(true)
        //        ;
        yield ChoiceField::new('isEnabled', t('dashboard.menu.bonus.crud.item.field.is_enabled.label', domain: 'admin'))
            ->setChoices([
                'Non' => 0,
                'Oui' => 1,
            ])
            ->setRequired(true)
        ;

        yield IntegerField::new('cumulativeTimes', t('dashboard.menu.bonus.crud.item.field.cumulative_times.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\Positive(),
            ])
            ->setHelp(t('dashboard.menu.bonus.crud.item.field.cumulative_times.help', domain: 'admin'))
            ->hideOnIndex()
        ;

        yield IntegerField::new('sort', t('dashboard.menu.bonus.crud.item.field.sort.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\Positive(),
            ])
            ->setHelp(t('dashboard.menu.bonus.crud.item.field.sort.help', domain: 'admin'))
            ->setRequired(true)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, t('dashboard.menu.bonus.crud.index.title', domain: 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, static fn (Bonus $bonus) => t('dashboard.menu.bonus.crud.detail.title', ['%bonus%' => $bonus->getName()], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, static fn (Bonus $bonus) => t('dashboard.menu.bonus.crud.edit.title', ['%bonus%' => $bonus->getName()], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, t('dashboard.menu.bonus.crud.new.title', domain: 'admin'))
            ->setDefaultSort(['createdAt' => Criteria::DESC])
            ->setSearchFields(['firstName', 'lastName', 'email'])
            ->setEntityPermission(RoleEnum::ROLE_SUPER_ADMIN->value)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
        ;
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'bonus_repository' => BonusRepositoryInterface::class,
        ]);
    }

    public static function getEntityFqcn(): string
    {
        return Bonus::class;
    }
}
