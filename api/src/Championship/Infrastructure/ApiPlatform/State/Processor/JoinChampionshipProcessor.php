<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Championship\Application\Command\Start\StartChampionshipCommand;
use App\Championship\Application\Dto\JoinChampionshipDto;
use App\Championship\Application\Query\GetByInvitationCode\GetChampionshipByInvitationCodeQuery;
use App\Championship\Domain\Exception\ChampionshipException;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Infrastructure\ApiPlatform\Resource\ChampionshipResource;
use App\CreditWallet\Application\Command\MakeTransaction\MakeTransactionCommand;
use App\CreditWallet\Domain\Enum\TransactionType;
use App\Parameter\Application\Query\Get\GetParameterQuery;
use App\Parameter\Domain\Model\ParameterInterface;
use App\Player\Application\Command\Create\CreatePlayerCommand;
use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

/**
 * @implements ProcessorInterface<JoinChampionshipDto, ChampionshipResource>
 */
final readonly class JoinChampionshipProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private Security $security,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ChampionshipResource
    {
        $invitationCode = $uriVariables['invitationCode'];
        Assert::isInstanceOf($data, JoinChampionshipDto::class);

        $errors = $this->validator->validate($data);
        if (\count($errors) > 0) {
            /* @phpstan-ignore-next-line Fixed in Symfony 7.0 */
            throw ChampionshipException::invalidData((string) $errors);
        }

        /** @var ChampionshipInterface $championship */
        $championship = $this->queryBus->ask(new GetChampionshipByInvitationCodeQuery($invitationCode));

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();

        $havePlayer = $user->getPlayers()->count();

        /** @var PlayerInterface $player */
        $player = $this->commandBus->dispatch(new CreatePlayerCommand(
            $user,
            $championship,
            $data->playerName
        ));

        $this->entityManager->persist($player);

        if ($championship->getPlayers()->count() === $championship->getNumberOfPlayers()->value) {
            $championship = $this->commandBus->dispatch(new StartChampionshipCommand(
                $championship,
                $user,
            ));

            $this->entityManager->persist($championship);
        }

        $this->entityManager->flush();

        if (!$havePlayer) {
            /** @var ParameterInterface $parameter */
            $parameter = $this->queryBus->ask(new GetParameterQuery(
                'reward_sponsorship',
            ));
            $this->commandBus->dispatch(new MakeTransactionCommand(
                $championship->getCreatedBy()->getCreditWallet()->getUuid(),
                TransactionType::CREDIT_SPONSORSHIP,
                (int) $parameter->getValue(),
            ));
        }

        return ChampionshipResource::fromModel($championship);
    }
}
