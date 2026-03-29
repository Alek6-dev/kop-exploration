<?php

declare(strict_types=1);

namespace App\Bonus\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Bonus\Application\Command\SelectBonus\SelectBonusCommand;
use App\Bonus\Application\Command\UnselectBonus\UnselectBonusCommand;
use App\Bonus\Application\Dto\SelectBonusDto;
use App\Bonus\Application\Query\Get\GetBonusQuery;
use App\Bonus\Domain\Enum\BonusTypeEnum;
use App\Bonus\Domain\Exception\BonusException;
use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Bonus\Domain\Model\BonusInterface;
use App\Duel\Application\Query\GetByUuid\GetDuelByUuidQuery;
use App\Duel\Domain\Model\DuelInterface;
use App\Player\Application\Query\Get\GetPlayerQuery;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Strategy\Application\Query\GetByUuid\GetStrategyByUuidQuery;
use App\Strategy\Domain\Model\StrategyInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

/**
 * @implements ProcessorInterface<SelectBonusDto, void>
 */
final readonly class SelectBonusProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private ValidatorInterface $validator,
        private Security $security,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        Assert::isInstanceOf($data, SelectBonusDto::class);

        $errors = $this->validator->validate($data);
        if (\count($errors) > 0) {
            /* @phpstan-ignore-next-line Fixed in Symfony 7.0 */
            throw BonusException::invalidData((string) $errors);
        }
        /** @var BonusInterface $bonus */
        $bonus = $this->queryBus->ask(new GetBonusQuery($data->bonusUuid));

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();
        switch ($data->type) {
            case BonusTypeEnum::STRATEGY:
                /** @var StrategyInterface $entity */
                $entity = $this->queryBus->ask(new GetStrategyByUuidQuery($data->entityUuid));
                $player = $entity->getPlayer();
                $bonusApplication = $entity->getBonusApplication();
                if ($player->getUser() !== $user) {
                    throw BonusException::impossibleToSelect();
                }
                break;
            case BonusTypeEnum::DUEL:
                /** @var DuelInterface $entity */
                $entity = $this->queryBus->ask(new GetDuelByUuidQuery($data->entityUuid));
                if ($entity->getPlayer1()->getUser() === $user) {
                    $player = $entity->getPlayer1();
                    $bonusApplication = $entity->getBonusApplicationByPlayer1OnRace($player->getChampionship()->getActiveChampionshipRace()->getRace());
                } elseif ($entity->getPlayer2()->getUser() === $user) {
                    $player = $entity->getPlayer2();
                    $bonusApplication = $entity->getBonusApplicationByPlayer2OnRace($player->getChampionship()->getActiveChampionshipRace()->getRace());
                } else {
                    throw BonusException::impossibleToSelect();
                }
                break;
            default:
                throw BonusException::wrongType($data->type->value);
        }

        $target = $data->targetUuid ? $this->queryBus->ask(new GetPlayerQuery($data->targetUuid)) : null;
        if ($bonusApplication) {
            $this->commandBus->dispatch(new UnselectBonusCommand(
                $entity,
                $player,
            ));
            $this->entityManager->refresh($entity);
        }

        /** @var BonusApplicationInterface $bonusSelected */
        $bonusSelected = $this->commandBus->dispatch(new SelectBonusCommand(
            $bonus,
            $player,
            $entity,
            $target,
        ));

        $this->entityManager->persist($bonusSelected);
        $this->entityManager->flush();
    }
}
