<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\CreditWallet\Domain\Model\CreditPackInterface;
use App\CreditWallet\Domain\Repository\CreditPackRepositoryInterface;
use App\CreditWallet\Infrastructure\Doctrine\Entity\CreditPack;
use App\Shared\Domain\Enum\User\RoleEnum;
use Doctrine\Common\Collections\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\Translation\t;

final class CreditPackCrudController extends AbstractCrudController
{
    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('credit', t('dashboard.menu.credit_pack.crud.item.field.credit.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;

        yield IntegerField::new('price', t('dashboard.menu.credit_pack.crud.item.field.price.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\PositiveOrZero(),
            ])
            ->setRequired(true)
        ;

        yield TextareaField::new('message', t('dashboard.menu.credit_pack.crud.item.field.message.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;

        yield TextField::new('productId', t('dashboard.menu.credit_pack.crud.item.field.product_id.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setDisabled()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, t('dashboard.menu.credit_pack.crud.index.title', domain: 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, static fn (CreditPackInterface $creditPack) => t('dashboard.menu.credit_pack.crud.detail.title', ['%credit_pack%' => $creditPack->getCredit()], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, static fn (CreditPackInterface $creditPack) => t('dashboard.menu.credit_pack.crud.edit.title', ['%credit_pack%' => $creditPack->getCredit()], 'admin'))
            ->setDefaultSort(['credit' => Order::Descending->value])
            ->setEntityPermission(RoleEnum::ROLE_SUPER_ADMIN->value)
        ;
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'credit_pack_repository' => CreditPackRepositoryInterface::class,
        ]);
    }

    public static function getEntityFqcn(): string
    {
        return CreditPack::class;
    }
}
