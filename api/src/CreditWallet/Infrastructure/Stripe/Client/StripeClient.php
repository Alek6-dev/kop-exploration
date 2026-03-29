<?php

namespace App\CreditWallet\Infrastructure\Stripe\Client;

use App\CreditWallet\Application\Command\MakeTransaction\MakeTransactionCommand;
use App\CreditWallet\Application\Dto\CreditPackFromStripeDto;
use App\CreditWallet\Application\Dto\StripeLinkCheckoutDto;
use App\CreditWallet\Domain\Enum\TransactionType;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\Log\Traits\LoggerTrait;
use App\User\Application\Query\Get\GetUserVisitorQuery;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Event;
use Stripe\Payout;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Stripe\Webhook;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;

class StripeClient
{
    use LoggerTrait;

    public const string URL_CALLBACK_SUCCESS_PARAMETER = 'success';
    public const string URL_CALLBACK_SUCCESS_VALUE = '{SUCCESS}';
    public const string METADATA_USER_ACCOUNT_ID_KEY = 'user_account_id';
    public const string METADATA_CREDITS_KEY = 'credits';
    public const string METADATA_CREDITS_QUANTITY_KEY = 'credits_quantity';

    public function __construct(
        private readonly UserVisitorRepositoryInterface $userVisitorRepository,
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
        #[Autowire(env: 'STRIPE_SECRET')]
        private readonly string $stripeSecret,
        #[Autowire(env: 'STRIPE_PAYMENT_CONFIRM_WEBHOOK_SECRET')]
        private readonly string $stripePaymentConfirmWebhookSecret,
    ) {
    }

    public function generateCustomer(UserVisitorInterface $user): void
    {
        if (null === $user->getCreditWallet()->getExternalId()) {
            $customer = Customer::create([
                'email' => $user->getEmail(),
                'name' => $user->getLastName().' '.$user->getFirstName(),
            ]);

            $user->getCreditWallet()->setExternalId($customer->id);

            $this->userVisitorRepository->update($user);
        } else {
            Customer::update($user->getCreditWallet()->getExternalId(), [
                'email' => $user->getEmail(),
            ]);
        }
    }

    public function generateCheckoutLink(UserVisitorInterface $user, StripeLinkCheckoutDto $dto): string
    {
        Stripe::setApiKey($this->stripeSecret);

        $creditPackStripe = $this->getCreditPack($dto->productId);

        $this->generateCustomer($user);

        $urlCallback = $dto->urlCallBack.'?'.self::URL_CALLBACK_SUCCESS_PARAMETER.'='.self::URL_CALLBACK_SUCCESS_VALUE;

        $session = Session::create([
            'automatic_tax' => [
                'enabled' => true,
            ],
            'customer_update' => [
                'address' => 'auto',
            ],
            'line_items' => [
                [
                    'quantity' => 1,
                    'price' => $creditPackStripe->priceId,
                ],
            ],
            'invoice_creation' => [
                'enabled' => true,
            ],
            'metadata' => [
                self::METADATA_USER_ACCOUNT_ID_KEY => $user->getUuid(),
                self::METADATA_CREDITS_KEY => $dto->credit,
            ],
            'mode' => Session::MODE_PAYMENT,
            'customer' => $user->getCreditWallet()->getExternalId(),
            'success_url' => str_replace(self::URL_CALLBACK_SUCCESS_VALUE, '1', $urlCallback),
            'cancel_url' => str_replace(self::URL_CALLBACK_SUCCESS_VALUE, '0', $urlCallback),
        ]);

        return $session->url;
    }

    public function getCreditPack(string $productId): CreditPackFromStripeDto
    {
        Stripe::setApiKey($this->stripeSecret);

        $product = Product::retrieve($productId);
        $price = Price::retrieve($product->default_price);

        return CreditPackFromStripeDto::fromStripeData($product, $price);
    }

    public function confirmWebhookPayment(Request $request): bool
    {
        $signature = $request->headers->get('stripe-signature');

        $body = (string) $request->getContent();
        $event = Webhook::constructEvent(
            $body,
            $signature,
            $this->stripePaymentConfirmWebhookSecret,
        );

        /** @var Session $session */
        $session = $event->data['object'];
        $metadataUserAccountId = $session->metadata->{self::METADATA_USER_ACCOUNT_ID_KEY}; // @phpstan-ignore-line
        $metadataCredit = $session->metadata->{self::METADATA_CREDITS_KEY}; // @phpstan-ignore-line
        if (Event::TYPE_CHECKOUT_SESSION_COMPLETED !== $event->type) {
            $this->logger->critical(
                sprintf(
                    "La session stripe %s pour le paiement %s ne semble pas terminée, type d'event obtenu : %s. Les crédits ne peuvent être attribués au client : %s",
                    $session->id,
                    $session->payment_intent,
                    $event->type,
                    $metadataUserAccountId,
                )
            );

            return false;
        }

        if (Payout::STATUS_PAID !== $session->payment_status) {
            $this->logger->critical(
                sprintf(
                    'Le paiement %s pour la session stripe %s semble avoir échoué, statut du paiement obtenu : %s. Les crédits ne peuvent être attribués au client : %s',
                    $session->payment_intent,
                    $session->id,
                    $session->payment_status,
                    $metadataUserAccountId,
                )
            );

            return false;
        }

        try {
            /** @var UserVisitorInterface $user */
            $user = $this->queryBus->ask(new GetUserVisitorQuery($metadataUserAccountId));
        } catch (UserVisitorException) {
            $this->logger->critical(
                sprintf(
                    "L'utilisateur %s de la session stripe %s pour le paiement %s n'existe pas. Les crédits ne peuvent être attribués au client",
                    $metadataUserAccountId,
                    $session->id,
                    $session->payment_intent
                )
            );

            return false;
        }

        $this->commandBus->dispatch(new MakeTransactionCommand(
            $user->getCreditWallet()->getUuid(),
            TransactionType::CREDIT_WALLET,
            (int) $metadataCredit,
        ));

        return true;
    }
}
