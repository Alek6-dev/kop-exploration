<?php

declare(strict_types=1);

use App\CreditWallet\Infrastructure\Http\StripeConfirmWebHookController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('auth', '/auth')->methods(['POST']);
    $routes->add('webhook_stripe', '/payment/confirm')
        ->methods(['POST'])
        ->controller(StripeConfirmWebHookController::class);
};
