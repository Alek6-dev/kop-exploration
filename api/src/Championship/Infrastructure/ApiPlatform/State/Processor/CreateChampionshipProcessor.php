<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Championship\Application\Command\Create\CreateChampionshipCommand;
use App\Championship\Application\Dto\CreateChampionshipDto;
use App\Championship\Application\Query\InvitationCodes\GetChampionshipInvitationCodesQuery;
use App\Championship\Domain\Exception\ChampionshipException;
use App\Championship\Infrastructure\ApiPlatform\Resource\ChampionshipResource;
use App\Player\Application\Command\Create\CreatePlayerCommand;
use App\Player\Domain\Model\PlayerInterface;
use App\Season\Application\Query\Get\GetLastSeasonActiveQuery;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\Token\GenerateUniqueTokenCommand;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

/**
 * @implements ProcessorInterface<CreateChampionshipDto, ChampionshipResource>
 */
final readonly class CreateChampionshipProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private ValidatorInterface $validator,
        private Security $security,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ChampionshipResource
    {
        Assert::isInstanceOf($data, CreateChampionshipDto::class);

        $errors = $this->validator->validate($data);
        if (\count($errors) > 0) {
            /* @phpstan-ignore-next-line Fixed in Symfony 7.0 */
            throw ChampionshipException::invalidData((string) $errors);
        }

        /** @var SeasonInterface $season */
        $season = $this->queryBus->ask(new GetLastSeasonActiveQuery());

        /** @var array<string> $invitationCodes */
        $invitationCodes = $this->queryBus->ask(new GetChampionshipInvitationCodesQuery());

        /** @var string $uniqueToken */
        $uniqueToken = $this->commandBus->dispatch(new GenerateUniqueTokenCommand(forbiddenToken: $invitationCodes));

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();

        $championship = $this->commandBus->dispatch(new CreateChampionshipCommand(
            $season,
            $user,
            $data->name,
            $data->jokerEnabled,
            $data->championshipNumberRace,
            $data->championshipNumberPlayer,
            $uniqueToken
        ));

        $this->entityManager->persist($championship);

        /** @var PlayerInterface $player */
        $player = $this->commandBus->dispatch(new CreatePlayerCommand(
            $user,
            $championship,
            $data->playerName
        ));

        $this->entityManager->persist($player);

        $this->entityManager->flush();

        return ChampionshipResource::fromModel($championship);
    }
}
