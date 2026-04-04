<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\Championship\Infrastructure\Doctrine\Entity\Championship;
use App\CreditWallet\Application\Command\ExecuteCreditGrant\ExecuteCreditGrantCommand;
use App\CreditWallet\Domain\Enum\GrantTargetType;
use App\CreditWallet\Infrastructure\Doctrine\Entity\AdminCreditGrant;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Domain\Enum\User\RoleEnum;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use function Symfony\Component\Translation\t;

final class AdminCreditGrantCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return AdminCreditGrant::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('Attribution de crédits')
            ->setIcon('fa fa-coins');

        yield ChoiceField::new('targetType', 'Ciblage')
            ->setChoices(GrantTargetType::choices())
            ->setFormTypeOption('choice_value', static function (mixed $v): string {
                return $v instanceof GrantTargetType ? $v->value : (string) $v;
            })
            ->setFormTypeOption('attr', ['data-grant-target' => 'targetType'])
            ->setRequired(true)
        ;

        yield AssociationField::new('targetPlayer', 'Joueur ciblé')
            ->setFormTypeOption('by_reference', false)
            ->setFormTypeOption('query_builder', $this->em->createQueryBuilder()->select('u')->from(UserVisitor::class, 'u')->orderBy('u.email', 'ASC'))
            ->setFormTypeOption('attr', ['data-grant-field' => 'player'])
            ->hideOnIndex()
        ;

        yield AssociationField::new('targetChampionship', 'Championnat ciblé')
            ->setFormTypeOption('by_reference', false)
            ->setFormTypeOption('query_builder', $this->em->createQueryBuilder()->select('c')->from(Championship::class, 'c')->orderBy('c.name', 'ASC'))
            ->setFormTypeOption('attr', ['data-grant-target' => 'targetChampionship', 'data-grant-field' => 'championship'])
            ->hideOnIndex()
        ;

        yield AssociationField::new('excludedPlayers', 'Exclure ces joueurs')
            ->setFormTypeOption('by_reference', false)
            ->setFormTypeOption('query_builder', $this->em->createQueryBuilder()->select('u')->from(UserVisitor::class, 'u')->orderBy('u.email', 'ASC'))
            ->setFormTypeOption('attr', ['data-grant-target' => 'excludedPlayers', 'data-grant-field' => 'championship'])
            ->hideOnIndex()
        ;

        yield FormField::addPanel('Montant & motif');

        yield BooleanField::new('isDeduction', 'Déduction ?')
            ->renderAsSwitch(false)
            ->setFormTypeOption('attr', ['data-grant-target' => 'isDeduction'])
            ->addCssClass('grant-field-deduction')
        ;

        yield IntegerField::new('amount', 'Montant (crédits)')
            ->setFormTypeOption('attr', ['min' => 1])
            ->setRequired(true)
        ;

        yield TextField::new('reason', 'Motif')
            ->setRequired(true)
        ;

        yield DateTimeField::new('executedAt', 'Exécuté le')
            ->hideOnForm()
        ;

        yield DateTimeField::new('createdAt', 'Créé le')
            ->hideOnForm()
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, 'Attributions de crédits')
            ->setPageTitle(Crud::PAGE_NEW, 'Nouvelle attribution / déduction')
            ->setPageTitle(Crud::PAGE_DETAIL, static fn (AdminCreditGrant $g) => sprintf('Attribution — %s', $g->getReason()))
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setEntityPermission(RoleEnum::ROLE_SUPER_ADMIN->value)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->update(Crud::PAGE_INDEX, Action::NEW, static fn (Action $a) => $a->setLabel('Nouvelle attribution'))
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, static fn (Action $a) => $a->setLabel('Attribuer / Déduire les crédits'))
        ;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addHtmlContentToBody(<<<'HTML'
<script>
(function () {
    /**
     * Remonte depuis un <input>/<select> pour trouver le conteneur de champ
     * visible dans le DOM EasyAdmin (un div col-* ou form-group).
     * EasyAdmin 4 ne passe pas par form_row() donc row_attr n'atterrit pas
     * dans le DOM — on travaille directement sur l'élément input/select.
     */
    function getFieldContainer(el) {
        var p = el;
        for (var i = 0; i < 8; i++) {
            p = p.parentElement;
            if (!p) return null;
            var cls = p.className || '';
            if (typeof cls === 'string' && (cls.match(/\bcol-/) || cls === 'form-group')) {
                return p;
            }
        }
        return el.parentElement;
    }

    function setFieldVisible(dataGrantField, show) {
        document.querySelectorAll('[data-grant-field="' + dataGrantField + '"]').forEach(function (el) {
            var container = getFieldContainer(el);
            if (container) container.style.display = show ? '' : 'none';
        });
    }

    function grantFormInit() {
        var targetTypeSelect = document.querySelector('[data-grant-target="targetType"]');
        if (!targetTypeSelect) return;

        var isDeductionCheckbox = document.querySelector('[data-grant-target="isDeduction"]');

        // Bannière avertissement déduction — injectée une seule fois
        if (isDeductionCheckbox && !document.getElementById('deduction-warning')) {
            var warningDiv = document.createElement('div');
            warningDiv.id = 'deduction-warning';
            warningDiv.style.cssText = 'display:none;background:#7c2d12;color:#fff;padding:10px 16px;border-radius:6px;margin-bottom:12px;font-weight:bold;';
            warningDiv.textContent = '⚠️ MODE DÉDUCTION — Les crédits seront RETIRÉS du portefeuille des joueurs ciblés.';
            var deductionRow = isDeductionCheckbox.closest('.form-group') || isDeductionCheckbox.parentElement;
            if (deductionRow) deductionRow.insertAdjacentElement('beforebegin', warningDiv);
        }

        function updateVisibility() {
            var val = targetTypeSelect.value;
            setFieldVisible('player', val === 'player');
            setFieldVisible('championship', val === 'championship');
        }

        function updateDeductionWarning() {
            var w = document.getElementById('deduction-warning');
            if (!isDeductionCheckbox || !w) return;
            w.style.display = isDeductionCheckbox.checked ? '' : 'none';
        }

        function loadChampionshipPlayers() {
            var champSelect = document.querySelector('[data-grant-target="targetChampionship"]');
            if (!champSelect || !champSelect.value) return;

            fetch('/admin/api/championship-by-id/' + champSelect.value + '/players')
                .then(function (r) { return r.ok ? r.json() : []; })
                .then(function (players) {
                    var excludedSelect = document.querySelector('[data-grant-target="excludedPlayers"]');
                    if (!excludedSelect) return;
                    excludedSelect.innerHTML = '';
                    players.forEach(function (p) {
                        var opt = document.createElement('option');
                        opt.value = p.id;
                        opt.textContent = p.label;
                        excludedSelect.appendChild(opt);
                    });
                });
        }

        targetTypeSelect.addEventListener('change', function () {
            updateVisibility();
            if (this.value === 'championship') loadChampionshipPlayers();
        });

        // Appel initial : masque les champs inutiles selon la valeur par défaut
        updateVisibility();

        if (isDeductionCheckbox) {
            isDeductionCheckbox.addEventListener('change', updateDeductionWarning);
        }

        var champSelect = document.querySelector('[data-grant-target="targetChampionship"]');
        if (champSelect) {
            champSelect.addEventListener('change', loadChampionshipPlayers);
        }
    }

    document.addEventListener('turbo:load', grantFormInit);
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', grantFormInit);
    } else {
        grantFormInit();
    }
}());
</script>
HTML);
    }

    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);

        if ($entityInstance instanceof AdminCreditGrant) {
            $this->commandBus->dispatch(new ExecuteCreditGrantCommand($entityInstance->getUuid()));
        }
    }
}
