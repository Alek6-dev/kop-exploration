<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController;

use App\Admin\Application\Query\Stat\CountChampionships\CountChampionshipsQuery;
use App\Admin\Application\Query\Stat\CountTransactionsByType\CountTransactionsByTypeQuery;
use App\Admin\Application\Query\Stat\CountUsers\CountUsersQuery;
use App\Admin\Infrastructure\Doctrine\Entity\UserAdmin;
use App\Bonus\Infrastructure\Doctrine\Entity\Bonus;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Cosmetic\Infrastructure\Doctrine\Entity\Cosmetic;
use App\CreditWallet\Domain\Enum\TransactionType;
use App\CreditWallet\Infrastructure\Doctrine\Entity\CreditPack;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\Parameter\Infrastructure\Doctrine\Entity\Parameter;
use App\Race\Infrastructure\Doctrine\Entity\Race;
use App\Result\Infrastructure\Doctrine\Entity\Result;
use App\Season\Infrastructure\Doctrine\Entity\Season;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Enum\User\StatusEnum;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Symfony\Component\Translation\t;

final class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        $statistics = [
            'count_users' => $this->queryBus->ask(new CountUsersQuery(StatusEnum::isActive())),
            'count_championship_active' => $this->queryBus->ask(new CountChampionshipsQuery(ChampionshipStatusEnum::isActiveStatus())),
            'count_championship_over' => $this->queryBus->ask(new CountChampionshipsQuery([ChampionshipStatusEnum::OVER])),
            //            'count_euros_won' => $this->queryBus->ask(new CountTransactionsByTypeQuery(TransactionType::)),
            'count_cosmetics_bought' => $this->queryBus->ask(new CountTransactionsByTypeQuery(TransactionType::CONSUME_COSMETIC)),
        ];

        return $this->render('Admin/Dashboard/index.html.twig', [
            'statistics' => $statistics,
        ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud(
            t('dashboard.menu.season.section', domain: 'admin'),
            '',
            Season::class,
        );

        yield MenuItem::linkToCrud(
            t('dashboard.menu.race.section', domain: 'admin'),
            '',
            Race::class,
        );

        yield MenuItem::linkToCrud(
            t('dashboard.menu.team.section', domain: 'admin'),
            '',
            Team::class,
        );

        yield MenuItem::linkToCrud(
            t('dashboard.menu.driver.section', domain: 'admin'),
            '',
            Driver::class,
        );

        yield MenuItem::linkToCrud(
            t('dashboard.menu.result.section', domain: 'admin'),
            '',
            Result::class,
        );

        yield MenuItem::linkToCrud(
            t('dashboard.menu.user.section', domain: 'admin'),
            '',
            UserVisitor::class,
        );

        yield MenuItem::linkToCrud(
            t('dashboard.menu.admin.section', domain: 'admin'),
            '',
            UserAdmin::class,
        );

        yield MenuItem::linkToCrud(
            t('dashboard.menu.cosmetic.section', domain: 'admin'),
            '',
            Cosmetic::class,
        );

        yield MenuItem::linkToCrud(
            t('dashboard.menu.bonus.section', domain: 'admin'),
            '',
            Bonus::class,
        );

        yield MenuItem::linkToCrud(
            t('dashboard.menu.credit_pack.section', domain: 'admin'),
            '',
            CreditPack::class,
        );

        yield MenuItem::linkToCrud(
            t('dashboard.menu.parameter.section', domain: 'admin'),
            '',
            Parameter::class,
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle($this->translator->trans('dashboard.title', domain: 'admin'))
        ;
    }
}
