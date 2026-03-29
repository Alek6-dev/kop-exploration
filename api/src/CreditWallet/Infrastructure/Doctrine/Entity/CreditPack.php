<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\Doctrine\Entity;

use App\CreditWallet\Domain\Model\CreditPackInterface;
use App\CreditWallet\Infrastructure\Doctrine\Repository\DoctrineCreditPackRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: DoctrineCreditPackRepository::class)]
class CreditPack implements CreditPackInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Column(type: Types::STRING, unique: true)]
    private ?string $productId = null;

    #[Column(type: Types::INTEGER)]
    private ?int $credit = null;

    #[Column(type: Types::FLOAT)]
    private ?float $price = null;

    #[Column(type: Types::STRING, nullable: true)]
    private ?string $message = null;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): static
    {
        $this->productId = $productId;

        return $this;
    }

    public function setCredit(int $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

    public function getCredit(): ?int
    {
        return $this->credit;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }
}
