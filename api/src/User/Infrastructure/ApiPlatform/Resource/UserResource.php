<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Cosmetic\Infrastructure\ApiPlatform\Resource\CosmeticPossessedResource;
use App\CreditWallet\Infrastructure\ApiPlatform\Resource\CreditWalletResource;
use App\User\Application\Dto\CreateUserDto;
use App\User\Application\Dto\ForgotPasswordDto;
use App\User\Application\Dto\ResetPasswordDto;
use App\User\Application\Dto\TokenDto;
use App\User\Application\Dto\UpdateUserDto;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Infrastructure\ApiPlatform\State\Processor\CreateUserVisitorProcessor;
use App\User\Infrastructure\ApiPlatform\State\Processor\DeleteUserVisitorProcessor;
use App\User\Infrastructure\ApiPlatform\State\Processor\ForgotPasswordProcessor;
use App\User\Infrastructure\ApiPlatform\State\Processor\ResetPasswordUserProcessor;
use App\User\Infrastructure\ApiPlatform\State\Processor\UpdateUserVisitorProcessor;
use App\User\Infrastructure\ApiPlatform\State\Processor\ValidAccountProcessor;
use App\User\Infrastructure\ApiPlatform\State\Provider\ResetPasswordUserItemProvider;
use App\User\Infrastructure\ApiPlatform\State\Provider\UserVisitorItemProvider;
use App\User\Infrastructure\ApiPlatform\State\Provider\ValidUserItemProvider;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'User',
    operations: [
        new Get(
            paginationEnabled: false,
            provider: UserVisitorItemProvider::class,
        ),
        new Post(
            uriTemplate: '/users/forgot-password',
            description: 'Send email to reset password.',
            security: "is_granted('PUBLIC_ACCESS')",
            input: ForgotPasswordDto::class,
            output: false,
            processor: ForgotPasswordProcessor::class,
        ),
        new Post(
            uriTemplate: '/users/validation/{token}',
            uriVariables: [
                'token' => 'string',
            ],
            description: 'Finalize account creation.',
            security: "is_granted('PUBLIC_ACCESS')",
            input: false,
            output: TokenDto::class,
            provider: ValidUserItemProvider::class,
            processor: ValidAccountProcessor::class,
        ),
        new Post(
            uriTemplate: '/users/forgot-password/{token}',
            uriVariables: [
                'token' => 'string',
            ],
            description: 'Reset password.',
            security: "is_granted('PUBLIC_ACCESS')",
            input: ResetPasswordDto::class,
            output: TokenDto::class,
            provider: ResetPasswordUserItemProvider::class,
            processor: ResetPasswordUserProcessor::class,
        ),
        new Post(
            uriTemplate: '/users/{uuid}',
            inputFormats: ['multipart' => ['multipart/form-data']],
            uriVariables: [
                'uuid' => 'string',
            ],
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            input: UpdateUserDto::class,
            output: TokenDto::class,
            processor: UpdateUserVisitorProcessor::class,
        ),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
            security: "is_granted('PUBLIC_ACCESS')",
            input: CreateUserDto::class,
            processor: CreateUserVisitorProcessor::class,
        ),
        new Post(
            uriTemplate: '/users/delete/{uuid}',
            uriVariables: [
                'uuid' => 'string',
            ],
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            input: false,
            output: false,
            provider: UserVisitorItemProvider::class,
            processor: DeleteUserVisitorProcessor::class,
        ),
    ],
    normalizationContext: [
        'skip_null_values' => false,
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class UserResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        public ?string $uuid = null,
        #[Assert\NotBlank]
        public ?string $image = null,
        #[Assert\NotBlank]
        public ?string $pseudo = null,
        #[Assert\NotBlank]
        #[Assert\Email]
        public ?string $email = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?CosmeticPossessedResource $carCosmetic = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?CosmeticPossessedResource $helmetCosmetic = null,
        #[ApiProperty(readableLink: true, writableLink: false, security: "is_granted('IS_AUTHENTICATED_FULLY') && object.uuid == user.getUuid()")]
        public ?CreditWalletResource $creditWallet = null,
    ) {
    }

    public static function fromModel(UserVisitorInterface $model): self
    {
        return new self(
            $model->getUuid(),
            $model->getRelativeImagePath(),
            $model->getPseudo(),
            $model->getEmail(),
            $model->getCarCosmetic() ? CosmeticPossessedResource::fromModel($model->getCarCosmetic()) : null,
            $model->getHelmetCosmetic() ? CosmeticPossessedResource::fromModel($model->getHelmetCosmetic()) : null,
            $model->getCreditWallet() ? CreditWalletResource::fromModel($model->getCreditWallet()) : null,
        );
    }
}
