<?php

declare(strict_types=1);

namespace App\CreditWallet\Application\Query\StripeLink;

use App\CreditWallet\Application\Dto\StripeLinkCheckoutDto;
use App\CreditWallet\Infrastructure\Stripe\Client\StripeClient;
use App\Shared\Application\Query\AsQueryHandler;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[AsQueryHandler]
final readonly class StripeLinkQueryHandler
{
    public function __construct(
        private StripeClient $stripeClient,
        private Security $security,
    ) {
    }

    public function __invoke(StripeLinkQuery $query): string
    {
        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();

        return $this->stripeClient->generateCheckoutLink($user, new StripeLinkCheckoutDto(
            $query->productId,
            $query->credit,
            $query->urlCallback,
        ));
    }
}
