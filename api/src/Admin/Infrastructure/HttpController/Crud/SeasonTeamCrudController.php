<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\Season\Domain\Repository\SeasonRaceRepositoryInterface;
use App\Season\Infrastructure\Doctrine\Entity\SeasonRace;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Domain\Repository\TeamRepositoryInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

use function Symfony\Component\Translation\t;

final class SeasonTeamCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly TeamRepositoryInterface $teamRepository,
    ) {
    }

    public function configureFields(string $pageName): iterable
    {
        yield ChoiceField::new('team', t('dashboard.menu.season_team.crud.item.field.team.label', domain: 'admin'))
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
            ->setRequired(true)
        ;
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'season_race_repository' => SeasonRaceRepositoryInterface::class,
        ]);
    }

    public static function getEntityFqcn(): string
    {
        return SeasonRace::class;
    }
}
