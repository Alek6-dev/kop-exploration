<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\Notification\Domain\Enum\NotificationTypeEnum;
use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Shared\Domain\Enum\User\RoleEnum;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\Translation\t;

final class NotificationCrudController extends AbstractCrudController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title', t('dashboard.menu.notification.crud.item.field.title.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [new Assert\NotBlank()])
            ->setRequired(true)
        ;

        yield TextareaField::new('body', t('dashboard.menu.notification.crud.item.field.body.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [new Assert\NotBlank()])
            ->setRequired(true)
            ->hideOnIndex()
        ;

        yield ChoiceField::new('type', t('dashboard.menu.notification.crud.item.field.type.label', domain: 'admin'))
            ->setChoices(NotificationTypeEnum::choices())
            ->setFormTypeOption('choice_value', static function (mixed $choice): string {
                if ($choice instanceof NotificationTypeEnum) {
                    return $choice->value;
                }

                return (string) $choice;
            })
            ->setRequired(true)
        ;

        yield BooleanField::new('isForAll', t('dashboard.menu.notification.crud.item.field.is_for_all.label', domain: 'admin'))
            ->renderAsSwitch(false)
        ;

        yield BooleanField::new('showAsPopup', t('dashboard.menu.notification.crud.item.field.show_as_popup.label', domain: 'admin'))
            ->renderAsSwitch(false)
            ->hideOnIndex()
        ;

        yield AssociationField::new('targets', t('dashboard.menu.notification.crud.item.field.targets.label', domain: 'admin'))
            ->setFormTypeOption('by_reference', false)
            ->setFormTypeOption('query_builder', $this->em->createQueryBuilder()->select('u')->from(UserVisitor::class, 'u')->orderBy('u.id', 'ASC'))
            ->hideOnIndex()
            ->setHelp('Ignorer si "Pour tous" est coché.')
        ;

        yield DateTimeField::new('scheduledAt', t('dashboard.menu.notification.crud.item.field.scheduled_at.label', domain: 'admin'))
            ->hideOnIndex()
            ->setHelp('Laisser vide pour un envoi immédiat.')
        ;

        yield DateTimeField::new('publishedAt', t('dashboard.menu.notification.crud.item.field.published_at.label', domain: 'admin'))
            ->hideOnForm()
        ;

        yield DateTimeField::new('createdAt', t('dashboard.menu.notification.crud.item.field.created_at.label', domain: 'admin'))
            ->hideOnForm()
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, t('dashboard.menu.notification.crud.index.title', domain: 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, static fn (Notification $n) => t('dashboard.menu.notification.crud.detail.title', ['%notification%' => $n->getTitle()], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, static fn (Notification $n) => t('dashboard.menu.notification.crud.edit.title', ['%notification%' => $n->getTitle()], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, t('dashboard.menu.notification.crud.new.title', domain: 'admin'))
            ->setDefaultSort(['createdAt' => Criteria::DESC])
            ->setSearchFields(['title'])
            ->setEntityPermission(RoleEnum::ROLE_SUPER_ADMIN->value)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(
            ChoiceFilter::new('type', 'Type')
                ->setChoices(NotificationTypeEnum::choices())
        );
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        if ($entityInstance instanceof Notification && null === $entityInstance->getScheduledAt()) {
            $entityInstance->setPublishedAt(new \DateTimeImmutable());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public static function getEntityFqcn(): string
    {
        return Notification::class;
    }
}
