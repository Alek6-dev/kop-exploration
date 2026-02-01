<?php

declare(strict_types=1);

namespace App\Player\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Championship\Application\Query\Get\GetChampionshipQuery;
use App\Championship\Domain\Exception\ChampionshipException;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Player\Infrastructure\ApiPlatform\Resource\PlayerResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<PlayerResource>
 */
final readonly class PlayerItemByChampionshipProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): PlayerResource
    {
        /** @var string $uuid */
        $uuid = $uriVariables['championshipUuid'];

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();
        /** @var ChampionshipInterface $model */
        $model = $this->queryBus->ask(new GetChampionshipQuery(
            $uuid,
        ));

        if (!$model->isPlayer($user)) {
            throw ChampionshipException::notAPlayer($user->getUuid());
        }

        return PlayerResource::fromModel($model->getPlayer($user));
    }
}
