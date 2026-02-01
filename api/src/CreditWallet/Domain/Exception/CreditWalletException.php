<?php

declare(strict_types=1);

namespace App\CreditWallet\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class CreditWalletException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $walletId): self
    {
        throw new self(sprintf('There is no wallet with id %s.', $walletId));
    }

    public static function insufficientCredit(int $count, int $cost): self
    {
        throw new self(sprintf('Insufficient credits (actual: %s, expected: %s)', $count, $cost));
    }
}
