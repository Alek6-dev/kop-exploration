<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\Doctrine\Entity;

use App\CreditWallet\Domain\Enum\TransactionOperator;
use App\CreditWallet\Domain\Enum\TransactionType;
use App\CreditWallet\Domain\Exception\CreditWalletException;
use App\CreditWallet\Domain\Model\CreditWalletInterface;
use App\CreditWallet\Infrastructure\Doctrine\Repository\DoctrineCreditWalletRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: DoctrineCreditWalletRepository::class)]
class CreditWallet implements CreditWalletInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Column(type: Types::INTEGER)]
    private ?int $credit = null;

    #[Column(type: Types::STRING, nullable: true)]
    private ?string $externalId = null;

    #[ORM\OneToMany(mappedBy: 'creditWallet', targetEntity: CreditWalletTransaction::class, cascade: ['persist'])]
    private Collection $transactions;

    #[ORM\OneToOne(inversedBy: 'creditWallet', targetEntity: UserVisitor::class)]
    private ?UserVisitorInterface $user = null;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
        $this->transactions = new ArrayCollection();
    }

    public function getCredit(): ?int
    {
        return $this->credit;
    }

    public function setCredit(int $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

    private function consume(int $cost): self
    {
        if ($this->credit < $cost) {
            throw CreditWalletException::insufficientCredit($this->credit, $cost);
        }

        $this->credit -= $cost;

        return $this;
    }

    private function credit(int $quantity): self
    {
        $this->credit += abs($quantity);

        return $this;
    }

    public function makeTransaction(TransactionType $transactionType, int $cost): self
    {
        $creditBefore = $this->credit;
        $transactionOperator = TransactionOperator::getOperatorByType($transactionType);
        match ($transactionOperator) {
            TransactionOperator::CREDIT => $this->credit($cost),
            TransactionOperator::CONSUME => $this->consume($cost),
        };
        $creditTransaction = (new CreditWalletTransaction())
            ->setType($transactionType)
            ->setOperator($transactionOperator)
            ->setBalanceBefore($creditBefore)
            ->setBalanceAfter($this->credit)
            ->setCreditWallet($this)
        ;
        $this->addTransaction($creditTransaction);

        return $this;
    }

    public function hasCredits(): bool
    {
        return $this->credit > 0;
    }

    public function hasTransactions(): bool
    {
        return $this->transactions->count() > 0;
    }

    private function addTransaction(CreditWalletTransaction $transaction): self
    {
        $this->transactions->add($transaction);

        return $this;
    }

    public function getUser(): ?UserVisitorInterface
    {
        return $this->user;
    }

    public function setUser(UserVisitorInterface $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }
}
