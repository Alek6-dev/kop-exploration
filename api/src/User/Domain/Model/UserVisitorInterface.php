<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Cosmetic\Domain\Model\CosmeticPossessedInterface;
use App\CreditWallet\Domain\Model\CreditWalletInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Domain\Model\Behaviors\HasImage;
use App\Shared\Domain\Model\Behaviors\UserInterface;
use Doctrine\Common\Collections\Collection;

interface UserVisitorInterface extends UserInterface, HasImage
{
    public function getPseudo(): ?string;

    public function setPseudo(string $pseudo): static;

    /**
     * @return Collection<int, PlayerInterface>
     */
    public function getPlayers(): Collection;

    public function getCreditWallet(): ?CreditWalletInterface;

    public function setCreditWallet(CreditWalletInterface $creditWallet): static;

    public function confirm(): static;

    public function validate(): static;

    public function delete(): static;

    public function getEmailValidationToken(): ?string;

    public function setEmailValidationToken(?string $emailValidationToken): static;

    /**
     * @return Collection<int, CosmeticPossessedInterface>
     */
    public function getCosmeticsPossessed(): Collection;

    /**
     * @return Collection<int, CosmeticInterface>
     */
    public function getCosmetics(): Collection;

    /**
     * @return Collection<int, CosmeticInterface>
     */
    public function getSelectedCosmetics(): Collection;

    public function getCarCosmetic(): ?CosmeticInterface;

    public function getHelmetCosmetic(): ?CosmeticInterface;

    public function getSuitCosmetic(): ?CosmeticInterface;

    public function addCosmeticPossessed(CosmeticPossessedInterface $cosmeticPossessed): static;

    public function removeCosmeticPossessed(CosmeticPossessedInterface $cosmeticPossessed): void;
}
