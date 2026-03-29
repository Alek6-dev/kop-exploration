<?php

namespace App\Duel\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Championship\Application\Query\Get\GetChampionshipQuery;
use App\Championship\Domain\Exception\ChampionshipException;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Driver\Application\Query\Get\GetDriverQuery;
use App\Driver\Domain\Model\DriverInterface;
use App\Duel\Application\Command\SelectDriver\SelectDriverCommand;
use App\Duel\Application\Dto\SelectDriverDto;
use App\Duel\Application\Query\Get\GetDuelQuery;
use App\Duel\Domain\Exception\DuelException;
use App\Player\Infrastructure\ApiPlatform\Resource\PlayerResource;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

readonly class SelectDriverDuelProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private ValidatorInterface $validator,
        private Security $security,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): PlayerResource
    {
        Assert::isInstanceOf($data, SelectDriverDto::class);

        $errors = $this->validator->validate($data);
        if (\count($errors) > 0) {
            /* @phpstan-ignore-next-line Fixed in Symfony 7.0 */
            throw DuelException::invalidData((string) $errors);
        }

        /** @var ChampionshipInterface $championship */
        $championship = $this->queryBus->ask(new GetChampionshipQuery(
            $uriVariables['championshipUuid']
        ));

        /** @var DriverInterface $driver */
        $driver = $this->queryBus->ask(new GetDriverQuery(
            $data->driverUuid
        ));

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();
        if (!$championship->isPlayer($user)) {
            throw UserVisitorException::notPlayer($user->getUuid());
        }

        $player = $championship->getPlayer($user);
        $championshipRace = $player
            ->getChampionship()
            ->getActiveChampionshipRace();
        if (!$championshipRace) {
            throw ChampionshipException::noActiveRace($player->getChampionship()->getUuid());
        }

        $duel = $this->queryBus->ask(new GetDuelQuery(
            $player,
            $championship,
            $championshipRace->getRace()
        ));

        $duel = $this->commandBus->dispatch(new SelectDriverCommand(
            $duel,
            $player,
            $driver,
        ));

        $this->entityManager->persist($duel);
        $this->entityManager->flush();

        return PlayerResource::fromModel($player);
    }
}
