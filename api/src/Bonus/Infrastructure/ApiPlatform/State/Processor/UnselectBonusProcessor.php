<?php

declare(strict_types=1);

namespace App\Bonus\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Bonus\Application\Command\UnselectBonus\UnselectBonusCommand;
use App\Bonus\Application\Dto\UnselectBonusDto;
use App\Bonus\Domain\Enum\BonusTypeEnum;
use App\Bonus\Domain\Exception\BonusException;
use App\Duel\Application\Query\GetByUuid\GetDuelByUuidQuery;
use App\Duel\Domain\Model\DuelInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Strategy\Application\Query\GetByUuid\GetStrategyByUuidQuery;
use App\Strategy\Domain\Model\StrategyInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

/**
 * @implements ProcessorInterface<UnselectBonusDto, void>
 */
final readonly class UnselectBonusProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private ValidatorInterface $validator,
        private Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        Assert::isInstanceOf($data, UnselectBonusDto::class);

        $errors = $this->validator->validate($data);
        if (\count($errors) > 0) {
            /* @phpstan-ignore-next-line Fixed in Symfony 7.0 */
            throw BonusException::invalidData((string) $errors);
        }

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();
        switch ($data->type) {
            case BonusTypeEnum::STRATEGY:
                /** @var StrategyInterface $entity */
                $entity = $this->queryBus->ask(new GetStrategyByUuidQuery($data->entityUuid));
                if ($entity->getPlayer()->getUser() !== $user) {
                    throw BonusException::impossibleToUnselect();
                }
                break;
            case BonusTypeEnum::DUEL:
                /** @var DuelInterface $entity */
                $entity = $this->queryBus->ask(new GetDuelByUuidQuery($data->entityUuid));
                $entity->getChampionship()->getPlayer($user);
                if ($entity->getPlayer1()->getUser() !== $user && $entity->getPlayer2()->getUser() !== $user) {
                    throw BonusException::impossibleToUnselect();
                }
                break;
            default:
                throw BonusException::wrongType($data->type->value);
        }

        $this->commandBus->dispatch(new UnselectBonusCommand(
            $entity,
            $entity->getChampionship()->getPlayer($user),
        ));
    }
}
