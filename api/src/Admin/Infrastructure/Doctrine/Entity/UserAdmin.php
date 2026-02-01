<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Doctrine\Entity;

use App\Admin\Domain\Model\UserAdminInterface;
use App\Admin\Infrastructure\Doctrine\Repository\DoctrineUserAdminRepository;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\IdableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\TimestampableTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UserTrait;
use App\Shared\Infrastructure\Doctrine\Entity\Traits\UuidableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: DoctrineUserAdminRepository::class)]
#[UniqueEntity(
    fields: ['email'],
    message: 'This email is already in use.',
    errorPath: 'email',
)]
class UserAdmin implements UserAdminInterface
{
    use IdableTrait;
    use TimestampableTrait;
    use UserTrait;
    use UuidableTrait;

    public function __construct()
    {
        $this->uuid = (string) new UuidV4();
    }

    public function __toString(): string
    {
        return (string) $this->email;
    }
}
