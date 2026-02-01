<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\User\Application\Dto\UserStatisticDto;
use App\User\Infrastructure\ApiPlatform\State\Provider\UserStatisticCollectionProvider;

#[ApiResource(
    shortName: 'UserStatistic',
    operations: [
        new GetCollection(
            uriTemplate: 'statistics/user/{uuid}',
            uriVariables: [
                'uuid' => 'string',
            ],
            paginationEnabled: false,
            output: UserStatisticDto::class,
            provider: UserStatisticCollectionProvider::class,
        ),
    ],
    normalizationContext: [
        'skip_null_values' => false,
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class UserStatisticResource
{
}
