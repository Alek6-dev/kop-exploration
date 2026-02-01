<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Championship\Application\Query\Get\GetChampionshipQuery;
use App\Championship\Domain\Exception\ChampionshipException;
use App\Championship\Infrastructure\ApiPlatform\Resource\ChampionshipResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<ChampionshipResource>
 */
final readonly class ChampionshipItemProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ChampionshipResource
    {
        /** @var string $uuid */
        $uuid = $uriVariables['uuid'];

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();

        $model = $this->queryBus->ask(new GetChampionshipQuery(
            $uuid,
        ));

        if (!$model->isPlayer($user)) {
            throw ChampionshipException::notAPlayer($user->getUuid());
        }

        return ChampionshipResource::fromModel($model);
    }
}
