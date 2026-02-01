<?php

declare(strict_types=1);

namespace App\User\Application\Command\Create;

use App\CreditWallet\Infrastructure\Doctrine\Entity\CreditWallet;
use App\Shared\Application\Command\AsCommandHandler;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommandHandler]
final readonly class CreateUserVisitorCommandHandler
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function __invoke(CreateUserVisitorCommand $command): UserVisitorInterface
    {
        $user = new UserVisitor();

        $wallet = (new CreditWallet())
            ->setCredit(0)
        ;

        return $user
            ->setPseudo($command->pseudo)
            ->setPassword($this->userPasswordHasher->hashPassword($user, $command->password))
            ->setEmail($command->email)
            ->setImage($command->image)
            ->setCreditWallet($wallet)
        ;
    }
}
