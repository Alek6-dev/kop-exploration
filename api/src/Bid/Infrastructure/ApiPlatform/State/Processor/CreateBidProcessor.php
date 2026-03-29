<?php

declare(strict_types=1);

namespace App\Bid\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Bid\Application\Command\AddBid\AddBidCommand;
use App\Bid\Application\Dto\AddBidDto;
use App\Bid\Domain\Exception\BidException;
use App\Championship\Application\Query\Get\GetChampionshipQuery;
use App\Championship\Domain\Exception\ChampionshipException;
use App\Driver\Application\Query\Get\GetDriverQuery;
use App\Player\Infrastructure\ApiPlatform\Resource\PlayerResource;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Team\Application\Query\Get\GetTeamQuery;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

/**
 * @implements ProcessorInterface<AddBidDto>
 */
final readonly class CreateBidProcessor implements ProcessorInterface
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
        Assert::isInstanceOf($data, AddBidDto::class);

        $errors = $this->validator->validate($data);
        if (\count($errors) > 0) {
            /* @phpstan-ignore-next-line Fixed in Symfony 7.0 */
            throw BidException::invalidData((string) $errors);
        }

        $championship = $this->queryBus->ask(new GetChampionshipQuery(
            $uriVariables['championshipUuid']
        ));

        if (!$championship) {
            throw ChampionshipException::notFound($uriVariables['championshipUuid']);
        }

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();
        $player = $championship->getPlayer($user);
        if (!$player) {
            throw ChampionshipException::notAPlayer($user->getUuid());
        }

        $driver1 = $data->driver1Uuid ? $this->queryBus->ask(new GetDriverQuery(
            $data->driver1Uuid
        )) : null;

        $driver2 = $data->driver2Uuid ? $this->queryBus->ask(new GetDriverQuery(
            $data->driver2Uuid
        )) : null;

        $team = $data->teamUuid ? $this->queryBus->ask(new GetTeamQuery(
            $data->teamUuid
        )) : null;

        $bettingRound = $this->commandBus->dispatch(new AddBidCommand(
            $championship,
            $player,
            $driver1,
            (int) $data->driver1BidAmount,
            $driver2,
            (int) $data->driver2BidAmount,
            $team,
            (int) $data->teamBidAmount,
        ));

        $this->entityManager->persist($bettingRound);
        $this->entityManager->flush();

        return PlayerResource::fromModel($player);
    }
}
