<?php

declare(strict_types=1);

namespace App\Bonus\Infrastructure\Doctrine\Entity;

use App\Bonus\Domain\Enum\AttributeEnum;
use App\Bonus\Domain\Enum\OperationEnum;
use App\Bonus\Domain\Enum\SubTargetTypeEnum;
use App\Bonus\Domain\Exception\BonusException;
use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Bonus\Domain\Model\BonusInterface;
use App\Bonus\Infrastructure\Doctrine\Repository\DoctrineBonusApplicationRepository;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Infrastructure\Doctrine\Entity\Championship;
use App\Duel\Domain\Model\DuelDriverPerformanceInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Duel\Infrastructure\Doctrine\Entity\Duel;
use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Performance\Domain\Model\TeamPerformanceInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use App\Race\Domain\Model\RaceInterface;
use App\Race\Infrastructure\Doctrine\Entity\Race;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use App\Strategy\Domain\Model\StrategyDriverPerformanceInterface;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Infrastructure\Doctrine\Entity\Strategy;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DoctrineBonusApplicationRepository::class)]
class BonusApplication implements BonusApplicationInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UuidableTrait;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Bonus::class)]
    private ?BonusInterface $bonus = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Player::class)]
    private ?PlayerInterface $target = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Championship::class)]
    private ?ChampionshipInterface $championship = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Race::class)]
    private ?RaceInterface $race = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Player::class)]
    private ?PlayerInterface $player = null;

    #[ORM\ManyToOne(targetEntity: Strategy::class)]
    private ?StrategyInterface $strategy = null;

    #[ORM\ManyToOne(targetEntity: Duel::class)]
    private ?DuelInterface $duel = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $balanceBefore = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $balanceAfter = null;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function getBonus(): ?BonusInterface
    {
        return $this->bonus;
    }

    public function setBonus(BonusInterface $bonus): static
    {
        $this->bonus = $bonus;

        return $this;
    }

    public function getTarget(): ?PlayerInterface
    {
        return $this->target;
    }

    public function setTarget(PlayerInterface $player): static
    {
        $this->target = $player;

        return $this;
    }

    public function getChampionship(): ?ChampionshipInterface
    {
        return $this->championship;
    }

    public function setChampionship(ChampionshipInterface $championship): static
    {
        $this->championship = $championship;

        return $this;
    }

    public function getRace(): RaceInterface
    {
        return $this->race;
    }

    public function setRace(RaceInterface $race): static
    {
        $this->race = $race;

        return $this;
    }

    public function getPlayer(): ?PlayerInterface
    {
        return $this->player;
    }

    public function setPlayer(PlayerInterface $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getStrategy(): ?StrategyInterface
    {
        return $this->strategy;
    }

    public function setStrategy(?StrategyInterface $strategy): static
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function getDuel(): ?DuelInterface
    {
        return $this->duel;
    }

    public function setDuel(?DuelInterface $duel): static
    {
        $this->duel = $duel;

        return $this;
    }

    public function getBalanceBefore(): ?int
    {
        return $this->balanceBefore;
    }

    public function setBalanceBefore(?int $value): static
    {
        $this->balanceBefore = $value;

        return $this;
    }

    public function getBalanceAfter(): ?int
    {
        return $this->balanceAfter;
    }

    public function setBalanceAfter(?int $value): static
    {
        $this->balanceAfter = $value;

        return $this;
    }

    public function applyBonusToPerformance(DriverPerformanceInterface|TeamPerformanceInterface $performance): static
    {
        $bonus = $this->bonus;
        if ($performance instanceof DriverPerformanceInterface) {
            $this->assertBonusOnDriverEntityIsValid($performance);
        }

        if ($performance instanceof TeamPerformanceInterface) {
            $this->assertBonusOnTeamEntityIsValid($performance);
        }

        $minValue = 0;
        switch ($bonus->getAttribute()) {
            case AttributeEnum::DRIVER_SCORE:
                $accessor = 'getScoreWithBonus';
                break;
            case AttributeEnum::TEAM_MULTIPLIER:
                $accessor = 'getMultiplier';
                $minValue = 11;
                break;
            default:
                throw BonusException::impossibleToApply();
        }

        $this->balanceBefore = $performance->{$accessor}();

        match ($bonus->getOperation()) {
            OperationEnum::MINUS => $balanceAfter = $performance->{$accessor}() - $bonus->getValue(),
            OperationEnum::PLUS => $balanceAfter = $performance->{$accessor}() + $bonus->getValue(),
            OperationEnum::MULTIPLIER => $balanceAfter = $performance->{$accessor}() * $bonus->getValue(),
            OperationEnum::DIVIDE => $balanceAfter = $performance->{$accessor}() / $bonus->getValue(),
            null => throw BonusException::impossibleToApply(),
        };

        $this->balanceAfter = max($minValue, (int) round($balanceAfter));

        return $this;
    }

    private function assertBonusOnDriverEntityIsValid(DriverPerformanceInterface $performance): void
    {
        $bonus = $this->bonus;
        if (!\in_array($bonus->getSubTargetType(), [SubTargetTypeEnum::DRIVER_1, SubTargetTypeEnum::DRIVER_2])) {
            throw BonusException::impossibleToApplyBonusDueToWrongDriverSelection($bonus->getUuid(), $performance->getUuid());
        }

        if ($this->target->getActiveSelectedDriver1() !== $performance->getDriver() && $this->target->getActiveSelectedDriver2() !== $performance->getDriver()) {
            throw BonusException::impossibleToApplyBonusDueToWrongDriverSelection($bonus->getUuid(), $performance->getUuid());
        }

        if ($performance instanceof StrategyDriverPerformanceInterface
            && ((SubTargetTypeEnum::DRIVER_1 === $bonus->getSubTargetType() && $performance->getDriver() !== $performance->getStrategy()->getDriver())
                || (SubTargetTypeEnum::DRIVER_2 === $bonus->getSubTargetType() && $performance->getDriver() === $performance->getStrategy()->getDriver()))) {
            throw BonusException::impossibleToApplyBonusDueToWrongDriverSelection($bonus->getUuid(), $performance->getUuid());
        }

        if (SubTargetTypeEnum::DRIVER_1 === $bonus->getSubTargetType() && $performance instanceof DuelDriverPerformanceInterface
            && ((
                $performance->getDuel()->getPlayer1() === $this->getTarget()
                && $performance->getDriver() !== $performance->getDuel()->getPlayerDriver1()
            ) || (
                $performance->getDuel()->getPlayer2() === $this->getTarget()
                && $performance->getDriver() !== $performance->getDuel()->getPlayerDriver2()
            )
            )) {
            throw BonusException::impossibleToApplyBonusDueToWrongDriverSelection($bonus->getUuid(), $performance->getUuid());
        }
    }

    private function assertBonusOnTeamEntityIsValid(TeamPerformanceInterface $performance): void
    {
        $bonus = $this->bonus;
        if (SubTargetTypeEnum::TEAM !== $bonus->getSubTargetType()) {
            throw BonusException::impossibleToApplyBonusDueToWrongDriverSelection($bonus->getUuid(), $performance->getUuid());
        }

        if ($performance->getTeam() !== $this->getTarget()->getSelectedTeam()) {
            throw BonusException::impossibleToApplyBonusDueToWrongTeamSelection($bonus->getUuid(), $performance->getUuid());
        }
    }
}
