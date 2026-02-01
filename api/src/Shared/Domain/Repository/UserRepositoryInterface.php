<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

use App\Shared\Domain\Enum\User\StatusEnum;

interface UserRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withStatus(StatusEnum $status): static;
}
