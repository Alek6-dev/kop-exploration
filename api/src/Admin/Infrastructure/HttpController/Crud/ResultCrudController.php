<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController\Crud;

use App\Admin\Infrastructure\Field\CsvFileField;
use App\Championship\Application\Command\UpdateChampionshipRaceStatus\UpdateChampionshipRaceStatusCommand;
use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Model\ChampionshipRaceInterface;
use App\Championship\Domain\Repository\ChampionshipRaceRepositoryInterface;
use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Domain\Repository\DriverRepositoryInterface;
use App\Performance\Application\Command\Generate\GeneratePerformanceCommand;
use App\Performance\Application\Command\SavePerformance\SaveDriverPerformanceCommand;
use App\Performance\Application\Command\SavePerformance\SaveTeamPerformanceCommand;
use App\Performance\Domain\Enum\TeamMultiplierEnum;
use App\Performance\Domain\Exception\PerformanceException;
use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Performance\Domain\Model\TeamPerformanceInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Race\Domain\Repository\RaceRepositoryInterface;
use App\Result\Domain\Enum\TypeResultEnum;
use App\Result\Domain\Model\ResultInterface;
use App\Result\Domain\Repository\ResultRepositoryInterface;
use App\Result\Infrastructure\Doctrine\Entity\Result;
use App\Result\Infrastructure\Doctrine\Entity\ResultLap;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Domain\Model\SeasonRaceInterface;
use App\Season\Domain\Repository\SeasonRaceRepositoryInterface;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\ImportData\ImportCsvDataCommand;
use App\Shared\Domain\Enum\User\RoleEnum;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\InsufficientEntityPermissionException;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Symfony\Component\Translation\t;

final class ResultCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly ResultRepositoryInterface $resultRepository,
        private readonly DriverRepositoryInterface $driverRepository,
        private readonly SeasonRaceRepositoryInterface $seasonRaceRepository,
        private readonly ChampionshipRaceRepositoryInterface $championshipRaceRepository,
        private readonly CommandBusInterface $commandBus,
        private readonly RaceRepositoryInterface $raceRepository,
        private readonly SeasonRepositoryInterface $seasonRepository,
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly TranslatorInterface $translator,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addFieldset('Course');
        yield ChoiceField::new('season', t('dashboard.menu.result.crud.item.field.season.label', domain: 'admin'))
            ->setFormTypeOption('choice_label', function (int|SeasonInterface|string $choice): string {
                return (string) $choice;
            })
            ->setFormTypeOption('choice_value', static function (int|SeasonInterface|string|null $value): ?string {
                if (null === $value) {
                    return null;
                }

                return (string) $value;
            })
            ->setChoices($this->seasonRepository->getAll())
            ->setRequired(true)
            ->hideOnDetail()
            ->hideOnIndex()
        ;

        yield DateTimeField::new('createdAt', t('dashboard.menu.result.crud.item.field.created_at.label', domain: 'admin'))
            ->setRequired(true)
            ->hideOnForm();

        yield AssociationField::new('season', t('dashboard.menu.result.crud.item.field.season.label', domain: 'admin'))
            ->setRequired(true)
            ->hideOnForm();

        yield ChoiceField::new('race', t('dashboard.menu.result.crud.item.field.race.label', domain: 'admin'))
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
            ->setRequired(true)
            ->hideOnDetail()
            ->hideOnIndex()
        ;
        yield AssociationField::new('race', t('dashboard.menu.result.crud.item.field.race.label', domain: 'admin'))
            ->setRequired(true)
            ->hideOnForm();

        yield CsvFileField::new('csv')
            ->setRequired(true)
            ->hideOnIndex()
            ->hideOnDetail()
        ;

        yield CollectionField::new('driverPerformances')
            ->setTemplatePath('Admin/Crud/Field/driver_performance.html.twig')
            ->hideOnForm()
            ->hideOnIndex()
        ;

        yield CollectionField::new('teamPerformances')
            ->setTemplatePath('Admin/Crud/Field/team_performance.html.twig')
            ->hideOnForm()
            ->hideOnIndex()
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, t('dashboard.menu.result.crud.index.title', domain: 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, static fn (Result $result) => t('dashboard.menu.result.crud.detail.title', ['%result%' => $result->getSeason().' '.$result->getRace()], 'admin'))
            ->setDefaultSort(['createdAt' => Order::Descending->value])
            ->setSearchFields(['season.name', 'race.name', 'type'])
            ->setEntityPermission(RoleEnum::ROLE_SUPER_ADMIN->value)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_DETAIL, $this->getDeleteAction('delete_result'))
            ->add(Crud::PAGE_DETAIL, $this->getGeneratePerformanceAction('generate_performance'))
            ->add(Crud::PAGE_NEW, $this->getImportAction())
        ;
    }

    private function getDeleteAction(string $name): Action
    {
        return Action::new($name)
            ->linkToCrudAction('deleteResult')
            ->setCssClass('btn btn-secondary text-danger')
            ->setIcon('fa fa-fw fa-trash-o')
            ->setLabel(t('dashboard.menu.result.crud.button.remove.label', domain: 'admin'))
        ;
    }

    private function getGeneratePerformanceAction(string $name): Action
    {
        return Action::new($name)
            ->linkToCrudAction('generatePerformance')
            ->setCssClass('btn btn-primary')
            ->setIcon('fa fa-fw fa-gears')
            ->setLabel(t('dashboard.menu.result.crud.button.generate_performance.label', domain: 'admin'))
        ;
    }

    private function getImportAction(): Action
    {
        return Action::new(Action::SAVE_AND_RETURN)
            ->linkToCrudAction('importResult')
            ->displayAsButton()
            ->setCssClass('btn-primary btn')
            ->setLabel(t('dashboard.menu.result.crud.button.import.label', domain: 'admin'))
        ;
    }

    public function deleteResult(AdminContext $context): RedirectResponse
    {
        try {
            /** @var ?ResultInterface $entity */
            $entity = $context->getEntity()->getInstance();
            if (!$entity) {
                throw new \Exception('Result not found.');
            }
            $this->resultRepository->removeManyBySeasonRaceType($entity->getSeason(), $entity->getRace());
            $this->addFlash('success', $this->translator->trans(
                'dashboard.menu.result.crud.success.remove',
                [
                    '%season%' => $entity->getSeason(),
                    '%race%' => $entity->getRace(),
                ],
                'admin'
            ));
        } catch (\Throwable) {
            $this->addFlash('danger', $this->translator->trans(
                'dashboard.menu.result.crud.error.remove.result',
                [
                    '%season%' => $entity->getSeason(),
                    '%race%' => $entity->getRace(),
                ],
                'admin'
            ));
        }

        return $this->redirect($this->adminUrlGenerator
            ->setController(self::class)
            ->setAction(Action::INDEX)
            ->setEntityId(null)
            ->generateUrl());
    }

    public function generatePerformance(AdminContext $context): RedirectResponse
    {
        try {
            /** @var ?ResultInterface $entity */
            $entity = $context->getEntity()->getInstance();
            if (!$entity) {
                throw new \Exception('Result not found.');
            }

            $this->commandBus->dispatch(new GeneratePerformanceCommand(
                $entity->getSeason(),
                $entity->getRace(),
            ));
            $this->addFlash('success', $this->translator->trans(
                'dashboard.menu.result.crud.success.generate_performance',
                [
                    '%season%' => $entity->getSeason(),
                    '%race%' => $entity->getRace(),
                ],
                'admin'
            ));
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            if (!$e instanceof PerformanceException) {
                $message = $this->translator->trans(
                    'dashboard.menu.result.crud.error.remove.generate_performance',
                    [
                        '%season%' => $entity->getSeason(),
                        '%race%' => $entity->getRace(),
                    ],
                    'admin'
                );
            }
            $this->addFlash('danger', $message);
        }

        return $this->redirect($this->adminUrlGenerator
            ->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($entity->getId())
            ->generateUrl());
    }

    #[\Override]
    public function new(AdminContext $context): KeyValueStore|RedirectResponse|Response
    {
        $event = new BeforeCrudActionEvent($context);
        $this->container->get('event_dispatcher')->dispatch($event);
        if ($event->isPropagationStopped()) {
            return $event->getResponse();
        }

        if (!$this->isGranted(Permission::EA_EXECUTE_ACTION, ['action' => Action::NEW, 'entity' => null])) {
            throw new ForbiddenActionException($context);
        }

        if (!$context->getEntity()->isAccessible()) {
            throw new InsufficientEntityPermissionException($context);
        }

        $context->getEntity()->setInstance($this->createEntity($context->getEntity()->getFqcn()));
        $this->container->get(EntityFactory::class)->processFields($context->getEntity(), FieldCollection::new($this->configureFields(Crud::PAGE_NEW)));
        $context->getCrud()->setFieldAssets($this->getFieldAssets($context->getEntity()->getFields()));
        $this->container->get(EntityFactory::class)->processActions($context->getEntity(), $context->getCrud()->getActionsConfig());

        $newForm = $this->createNewForm($context->getEntity(), $context->getCrud()->getNewFormOptions(), $context);
        $newForm->handleRequest($context->getRequest());

        $entityInstance = $newForm->getData();
        $context->getEntity()->setInstance($entityInstance);

        if ($newForm->isSubmitted() && $newForm->isValid()) {
            $this->processUploadedFiles($newForm);

            $event = new BeforeEntityPersistedEvent($entityInstance);
            $this->container->get('event_dispatcher')->dispatch($event);
            $entityInstance = $event->getEntityInstance();
            $file = $context->getRequest()->files->get('Result')['csv']['file'];
            try {
                /** @var SeasonInterface $season */
                $season = $entityInstance->getSeason();
                /** @var RaceInterface $race */
                $race = $entityInstance->getRace();
                /** @var ?SeasonRaceInterface $seasonRace */
                $seasonRace = $this->seasonRaceRepository->findOneBy(['race' => $race, 'season' => $season]);

                if (!$seasonRace) {
                    throw new \Exception($this->translator->trans('error.race_not_attached.label', ['%race_name%' => (string) $race, '%season_name%' => (string) $season], 'season'));
                }
                // TODO: Command Bus
                $result = (new Result())
                    ->setRace($race)
                    ->setSeason($season);
                // Initial position (T0) must be informed
                $noLapsExpected = $seasonRace->getLaps() + 1;
                $needSprint = (bool) $seasonRace->getSprintDate();
                $countDrivers = $season->getSeasonActiveDrivers()->count();

                $teams = [];
                $driverPerformances = [];
                $teamPerformances = [];
                $this->commandBus->dispatch(new ImportCsvDataCommand(
                    $file,
                    true,
                    function ($data) use (&$teams, &$driverPerformances, &$teamPerformances, &$result, $race, $season, $noLapsExpected, $needSprint, $countDrivers) {
                        // Treat one line
                        // First column must be driver (firstname lastname)
                        $driverName = $data[0];
                        /** @var ?DriverInterface $driver */
                        $driver = $this->driverRepository->withFullName($driverName)->first();
                        if (!$driver) {
                            throw new \Exception($this->translator->trans('error.not_found.label', ['%full_name%' => $driverName], 'driver'));
                        }

                        // 2e column must be the qualification position of the current driver
                        $positionQualification = $data[1];

                        $resultLap = (new ResultLap())
                            ->setNoLap(1)
                            ->setPlace($positionQualification)
                            ->setDriver($driver)
                            ->setTeam($driver->getTeam())
                            ->setType(TypeResultEnum::QUALIFICATION)
                        ;
                        $result->addResultLap($resultLap);

                        // 3e column must be (can be null if no Sprint) the Sprint position of the current driver
                        $positionSprint = empty($data[2]) ? null : $data[2];

                        if ($needSprint && !$positionSprint) {
                            throw new \Exception($this->translator->trans('dashboard.menu.result.crud.error.sprint_missing', ['%race%' => (string) $race, '%season%' => (string) $season], 'admin'));
                        }
                        if ($positionSprint) {
                            // TODO: Command Bus
                            $resultLap = (new ResultLap())
                                ->setNoLap(1)
                                ->setPlace($positionQualification)
                                ->setDriver($driver)
                                ->setTeam($driver->getTeam())
                                ->setType(TypeResultEnum::SPRINT)
                            ;
                            $result->addResultLap($resultLap);
                        }

                        // Next column are lap result on regular race
                        $noLaps = 0;
                        $previousLap = null;
                        $positionGain = 0;
                        $lastPosition = null;
                        foreach ($data as $key => $lapPosition) {
                            if (2 >= $key) {
                                continue;
                            }
                            if (null !== $previousLap && 'A' !== $lapPosition && 'A' !== $previousLap && (int) $previousLap > (int) $lapPosition) {
                                $positionGain += ((int) $previousLap - (int) $lapPosition);
                            }
                            $previousLap = $lapPosition;

                            ++$noLaps;
                            // TODO: Command Bus
                            $resultLap = (new ResultLap())
                                ->setNoLap($noLaps)
                                ->setPlace($lapPosition)
                                ->setDriver($driver)
                                ->setTeam($driver->getTeam())
                                ->setType(TypeResultEnum::NORMAL)
                            ;
                            $result->addResultLap($resultLap);

                            $lastPosition = 'A' === $lapPosition ? $countDrivers : (int) $lapPosition;
                        }
                        if ($noLapsExpected !== $noLaps) {
                            throw new \Exception($this->translator->trans('error.laps_missing.label', ['%driver_name%' => (string) $driverName, '%laps%' => $noLaps, '%laps_expected%' => $noLapsExpected], domain: 'result'));
                        }
                        $this->entityManager->persist($result);

                        $driverPerformance = $this->commandBus->dispatch(new SaveDriverPerformanceCommand(
                            $season,
                            $race,
                            $driver,
                            $result,
                            $positionQualification,
                            $positionGain,
                            $lastPosition,
                            $positionSprint,
                        ));
                        $teams[$driver->getTeam()->getId()][] = $driverPerformance;
                        $driverPerformances[$driver->getId()] = $driverPerformance;
                        if (2 <= \count($teams[$driver->getTeam()->getId()])) {
                            $teamPerformance = $this->commandBus->dispatch(new SaveTeamPerformanceCommand(
                                $season,
                                $race,
                                $driver->getTeam(),
                                $teams[$driver->getTeam()->getId()][0],
                                $teams[$driver->getTeam()->getId()][1],
                                $result,
                            ));
                            $teamPerformances[$driver->getTeam()->getId()] = [
                                'team_performance' => $teamPerformance,
                                'best_position' => min($teams[$driver->getTeam()->getId()][0]->getPosition(), $teams[$driver->getTeam()->getId()][1]->getPosition()),
                            ];
                        }
                    }
                ));
                // order driver performances by score, position DESC
                usort($driverPerformances, function (DriverPerformanceInterface $a, DriverPerformanceInterface $b) {
                    $comparison = (int) $a->getScore() <=> (int) $b->getScore();
                    if (0 === $comparison) {
                        $comparison = (int) $a->getPosition() <=> (int) $b->getPosition();
                    }

                    return $comparison;
                });
                // order team performances by score, the lower the team's score, the higher its place
                usort($teamPerformances, function (array $a, array $b) {
                    $comparison = (int) $b['team_performance']->getScore() <=> (int) $a['team_performance']->getScore();
                    if (0 === $comparison) {
                        $comparison = (int) $b['best_position'] <=> (int) $a['best_position'];
                    }

                    return $comparison;
                });
                /** @var array<DriverPerformanceInterface> $driverPerformances */
                $position = \count($driverPerformances);
                foreach ($driverPerformances as $driverPerformance) {
                    $driverPerformance->setPosition($position);
                    $this->entityManager->persist($driverPerformance);
                    --$position;
                }
                $position = \count($teamPerformances);
                foreach ($teamPerformances as $teamPerformance) {
                    /** @var TeamPerformanceInterface $teamPerformance */
                    // TODO: Command Bus
                    $teamPerformance = $teamPerformance['team_performance'];
                    $teamPerformance->setPosition($position);
                    $teamPerformance->setMultiplier(TeamMultiplierEnum::getPointsFromPosition((string) $position)->value);
                    $this->entityManager->persist($teamPerformance);
                    --$position;
                }

                $championshipRaces = $this->championshipRaceRepository
                    ->withStatus(ChampionshipRaceStatusEnum::WAITING_RESULT)
                    ->withRace($race)
                ;
                /** @var ChampionshipRaceInterface $championshipRace */
                foreach ($championshipRaces as $championshipRace) {
                    $championshipRace = $this->commandBus->dispatch(new UpdateChampionshipRaceStatusCommand(
                        $championshipRace,
                        ChampionshipRaceStatusEnum::RESULT_PROCESSED
                    ));
                    $this->entityManager->persist($championshipRace);
                }

                $this->entityManager->flush();
            } catch (\Throwable $exception) {
                $this->addFlash('danger', $exception->getMessage());
                goto response;
            }

            $this->container->get('event_dispatcher')->dispatch(new AfterEntityPersistedEvent($entityInstance));
            $context->getEntity()->setInstance($entityInstance);

            $this->addFlash('success', $this->translator->trans('dashboard.menu.result.crud.success.import', domain: 'admin'));

            response:
            $url = $this->adminUrlGenerator
                ->setController(self::class)
                ->setAction(Action::INDEX)
                ->generateUrl();

            return $this->redirect($url);
        }

        $responseParameters = $this->configureResponseParameters(KeyValueStore::new([
            'pageName' => Crud::PAGE_NEW,
            'templateName' => 'crud/new',
            'entity' => $context->getEntity(),
            'new_form' => $newForm,
        ]));

        $event = new AfterCrudActionEvent($context, $responseParameters);
        $this->container->get('event_dispatcher')->dispatch($event);
        if ($event->isPropagationStopped()) {
            return $event->getResponse();
        }

        return $responseParameters;
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'result_repository' => ResultRepositoryInterface::class,
        ]);
    }

    public static function getEntityFqcn(): string
    {
        return Result::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->addGroupBy('entity.season')
            ->addGroupBy('entity.race')
            ->distinct()
            ->addOrderBy('entity.season')
            ->addOrderBy('entity.race')
        ;
    }
}
