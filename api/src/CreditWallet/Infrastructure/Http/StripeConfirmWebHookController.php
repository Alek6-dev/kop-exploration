<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\Http;

use App\CreditWallet\Infrastructure\Stripe\Client\StripeClient;
use App\Shared\Infrastructure\Log\Traits\LoggerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Use Http Controller because ApiPlatform does not authorized Content-Type => application/json
 * => Update is not allowed for this operation.
 */
class StripeConfirmWebHookController extends AbstractController
{
    use LoggerTrait;

    public function __construct(
        private readonly StripeClient $stripeClient
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $this->stripeClient->confirmWebhookPayment($request);

        return new Response();
    }
}
