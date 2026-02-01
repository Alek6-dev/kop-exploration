<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\Race\Domain\Model\RaceInterface;
use App\Race\Domain\Repository\RaceRepositoryInterface;
use App\Season\Domain\Repository\SeasonRaceRepositoryInterface;
use App\Season\Infrastructure\Doctrine\Entity\SeasonRace;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\Translation\t;

final class SeasonRaceCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly RaceRepositoryInterface $raceRepository,
    ) {
    }

    public function configureFields(string $pageName): iterable
    {
        yield DateField::new('date', t('dashboard.menu.season_race.crud.item.field.date.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;

        yield DateField::new('qualificationDate', t('dashboard.menu.season_race.crud.item.field.qualification_date.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;

        yield DateField::new('sprintDate', t('dashboard.menu.season_race.crud.item.field.sprint_date.label', domain: 'admin'))
        ;

        yield DateTimeField::new('limitStrategyDate', t('dashboard.menu.season_race.crud.item.field.limit_strategy_date.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank(),
            ])
            ->setRequired(true)
        ;

        yield IntegerField::new('laps', t('dashboard.menu.season_race.crud.item.field.laps.label', domain: 'admin'))
            ->setFormTypeOption('constraints', [
                new Assert\Positive(),
            ])
            ->setHtmlAttribute('min', 0)
            ->setRequired(true)
        ;

        yield ChoiceField::new('race', t('dashboard.menu.season_race.crud.item.field.race.label', domain: 'admin'))
            ->setFormTypeOption('choice_label', function (int|RaceInterface|string $choice): string {
                return $choice->getName();
            })
            ->setFormTypeOption('choice_value', static function (int|RaceInterface|string|null $value): ?string {
                if (null === $value) {
                    return null;
                }

                return (string) ($value instanceof RaceInterface ? $value->getName() : $value);
            })
            ->setChoices($this->raceRepository->getAll())
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
