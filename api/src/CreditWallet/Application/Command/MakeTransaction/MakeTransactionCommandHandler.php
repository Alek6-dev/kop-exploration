<?php

namespace App\CreditWallet\Application\Command\MakeTransaction;

use App\CreditWallet\Domain\Exception\CreditWalletException;
use App\CreditWallet\Domain\Model\CreditWalletInterface;
use App\CreditWallet\Domain\Repository\CreditWalletRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
readonly class MakeTransactionCommandHandler
{
    public function __construct(
        private CreditWalletRepositoryInterface $repository
    ) {
    }

    public function __invoke(MakeTransactionCommand $command): void
    {
        /** @var ?CreditWalletInterface $wallet */
        $wallet = $this->repository->getByUuid($command->walletUuid);

        if (!$wallet) {
            throw CreditWalletException::notFound($command->walletUuid);
        }

        $wallet->makeTransaction($command->transactionType, $command->cost);

        $this->repository->update($wallet);
    }
}
