<?php

declare(strict_types=1);

namespace App\CreditWallet\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class CreditPackException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function notFound(string $walletId): self
    {
        throw new self(sprintf('There is no credit pack with id %s.', $walletId));
    }
}
