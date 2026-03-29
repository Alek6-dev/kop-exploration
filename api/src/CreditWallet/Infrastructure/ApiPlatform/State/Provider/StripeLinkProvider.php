<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\CreditWallet\Application\Query\Get\GetCreditPackQuery;
use App\CreditWallet\Application\Query\StripeLink\StripeLinkQuery;
use App\CreditWallet\Domain\Model\CreditPackInterface;
use App\CreditWallet\Infrastructure\ApiPlatform\Resource\StripeLinkResource;
use App\Shared\Application\Query\QueryBusInterface;

final readonly class StripeLinkProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $creditPackUuid = $context['filters']['credit_pack_id'];
        $urlCallback = $context['filters']['url_callback'];

        /** @var CreditPackInterface $creditPack */
        $creditPack = $this->queryBus->ask(new GetCreditPackQuery($creditPackUuid));

        $url = $this->queryBus->ask(new StripeLinkQuery(
            $creditPack->getProductId(),
            $creditPack->getCredit(),
            $urlCallback,
        ));

        return new StripeLinkResource($url);
    }
}
