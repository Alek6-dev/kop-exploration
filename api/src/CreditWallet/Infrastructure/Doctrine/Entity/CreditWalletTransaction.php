<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\Doctrine\Entity;

use App\CreditWallet\Domain\Enum\TransactionOperator;
use App\CreditWallet\Domain\Enum\TransactionType;
use App\CreditWallet\Domain\Model\CreditWalletInterface;
use App\CreditWallet\Domain\Model\CreditWalletTransactionInterface;
use App\CreditWallet\Infrastructure\Doctrine\Repository\DoctrineCreditWalletTransactionRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineCreditWalletTransactionRepository::class)]
class CreditWalletTransaction implements CreditWalletTransactionInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\Type(type: TransactionType::class)]
    #[Column(type: Types::STRING, enumType: TransactionType::class)]
    private ?TransactionType $type;

    #[Assert\Type(type: TransactionOperator::class)]
    #[Column(type: Types::STRING, enumType: TransactionOperator::class)]
    private ?TransactionOperator $operator;

    #[Assert\PositiveOrZero]
    #[Column(type: Types::INTEGER)]
    private int $balanceBefore = 0;

    #[Assert\PositiveOrZero]
    #[Column(type: Types::INTEGER)]
    private int $balanceAfter = 0;

    #[ORM\ManyToOne(targetEntity: CreditWallet::class, inversedBy: 'transactions')]
    private ?CreditWalletInterface $creditWallet;

    #[Column(type: Types::STRING, nullable: true)]
    private ?string $externalPaymentId = null;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getType(): ?TransactionType
    {
        return $this->type;
    }

    public function setType(TransactionType $transactionType): static
    {
        $this->type = $transactionType;

        return $this;
    }

    public function getOperator(): TransactionOperator
    {
        return $this->operator;
    }

    public function setOperator(?TransactionOperator $transactionOperator): static
    {
        $this->operator = $transactionOperator;

        return $this;
    }

    public function getBalanceBefore(): int
    {
        return $this->balanceBefore;
    }

    public function setBalanceBefore(int $balance): static
    {
        $this->balanceBefore = $balance;

        return $this;
    }

    public function getBalanceAfter(): int
    {
        return $this->balanceAfter;
    }

    public function setBalanceAfter(int $balance): static
    {
        $this->balanceAfter = $balance;

        return $this;
    }

    public function getExternalPaymentId(): ?string
    {
        return $this->externalPaymentId;
    }

    public function setExternalPaymentId(?string $externalPaymentId): static
    {
        $this->externalPaymentId = $externalPaymentId;

        return $this;
    }

    public function getCreditWallet(): ?CreditWalletInterface
    {
        return $this->creditWallet;
    }

    public function setCreditWallet(CreditWalletInterface $creditWallet): static
    {
        $this->creditWallet = $creditWallet;

        return $this;
    }
}
