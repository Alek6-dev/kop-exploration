<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Repository;

use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\UserRepositoryTrait;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class DoctrineUserVisitorRepository extends DoctrineRepository implements UserVisitorRepositoryInterface
{
    use CrudRepositoryTrait;
    use UserRepositoryTrait;
    private const string ENTITY_CLASS = UserVisitor::class;
    private const string ALIAS = 'user_visitor';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof UserVisitorInterface) {
            throw new UnsupportedUserException();
        }

        $user->setPassword($newHashedPassword);

        $this->update($user);
    }

    public function withEmail(string $email): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($email): void {
            $qb->andWhere(sprintf('%s.email = :email', self::ALIAS))->setParameter('email', $email);
        });
    }

    public function getByEmail(string $email): ?UserVisitorInterface
    {
        return $this->withEmail($email)->query()->getQuery()->getOneOrNullResult();
    }

    public function withEmailValidationToken(string $emailValidationToken): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($emailValidationToken): void {
            $qb->andWhere(sprintf('%s.emailValidationToken = :emailValidationToken', self::ALIAS))
                ->setParameter('emailValidationToken', $emailValidationToken);
        });
    }

    public function getByEmailValidationToken(string $emailValidationToken): ?UserVisitorInterface
    {
        return $this->withEmailValidationToken($emailValidationToken)
            ->query()
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function withResetPasswordToken(string $resetPasswordToken): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($resetPasswordToken): void {
            $qb->andWhere(sprintf('%s.resetPasswordToken = :resetPasswordToken', self::ALIAS))
                ->setParameter('resetPasswordToken', $resetPasswordToken);
        });
    }

    public function getByResetPasswordToken(string $resetPasswordToken): ?UserVisitorInterface
    {
        return $this->withResetPasswordToken($resetPasswordToken)->query()->getQuery()->getOneOrNullResult();
    }

    public function withStatuses(array $statuses): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($statuses): void {
            $qb->andWhere(sprintf('%s.status IN (:statuses)', self::ALIAS))
                ->setParameter('statuses', $statuses);
        });
    }
}
