<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\CreditWallet\Application\Dto\StripeLinkCheckoutDto;
use App\CreditWallet\Application\Query\Get\GetCreditPackQuery;
use App\CreditWallet\Application\Query\StripeLink\StripeLinkQuery;
use App\CreditWallet\Domain\Exception\CreditPackException;
use App\CreditWallet\Domain\Model\CreditPackInterface;
use App\CreditWallet\Infrastructure\ApiPlatform\Resource\StripeLinkResource;
use App\Shared\Application\Query\QueryBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

/**
 * @implements ProcessorInterface<StripeLinkCheckoutDto, StripeLinkResource>
 */
final readonly class StripeLinkProcessor implements ProcessorInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private ValidatorInterface $validator,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): StripeLinkResource
    {
        Assert::isInstanceOf($data, StripeLinkCheckoutDto::class);

        $errors = $this->validator->validate($data);
        if (\count($errors) > 0) {
            /* @phpstan-ignore-next-line Fixed in Symfony 7.0 */
            throw CreditPackException::invalidData((string) $errors);
        }

        /** @var CreditPackInterface $creditPack */
        $creditPack = $this->queryBus->ask(new GetCreditPackQuery($data->creditPackUuid));

        /** @var string $url */
        $url = $this->queryBus->ask(new StripeLinkQuery(
            $creditPack->getProductId(),
            $creditPack->getCredit(),
            $data->urlCallback,
        ));

        return new StripeLinkResource($url);
    }
}
