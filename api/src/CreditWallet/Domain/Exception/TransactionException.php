<?php

declare(strict_types=1);

namespace App\CreditWallet\Domain\Exception;

use App\Shared\Domain\Exception\InvalidDataExceptionTrait;

class TransactionException extends \Exception
{
    use InvalidDataExceptionTrait;

    public static function operatorNotFound(string $operator): self
    {
        throw new self(sprintf('Transaction operator %s not found.', $operator));
    }

    public static function typeNotFound(string $type): self
    {
        throw new self(sprintf('Transaction type %s not found.', $type));
    }
}
