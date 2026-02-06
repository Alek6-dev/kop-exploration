<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
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
    description: 'Gestion des comptes utilisateurs',
    operations: [
        new Get(
            paginationEnabled: false,
            provider: UserVisitorItemProvider::class,
            openapi: new Operation(
                summary: 'Profil utilisateur',
                description: 'Récupère les informations du profil de l\'utilisateur authentifié.',
                tags: ['Authentification'],
            ),
        ),
        new Post(
            uriTemplate: '/users/forgot-password',
            security: "is_granted('PUBLIC_ACCESS')",
            input: ForgotPasswordDto::class,
            output: false,
            processor: ForgotPasswordProcessor::class,
            openapi: new Operation(
                summary: 'Mot de passe oublié',
                description: 'Envoie un email avec un lien de réinitialisation du mot de passe.',
                tags: ['Authentification'],
                requestBody: new RequestBody(
                    description: 'Adresse email du compte',
                    required: true,
                ),
                responses: [
                    '204' => [
                        'description' => 'Email envoyé avec succès',
                    ],
                    '404' => [
                        'description' => 'Aucun compte trouvé avec cette adresse email',
                    ],
                ],
            ),
        ),
        new Post(
            uriTemplate: '/users/validation/{token}',
            uriVariables: [
                'token' => 'string',
            ],
            security: "is_granted('PUBLIC_ACCESS')",
            input: false,
            output: TokenDto::class,
            provider: ValidUserItemProvider::class,
            processor: ValidAccountProcessor::class,
            openapi: new Operation(
                summary: 'Valider le compte',
                description: 'Finalise la création du compte utilisateur via le token reçu par email.',
                tags: ['Authentification'],
                responses: [
                    '200' => [
                        'description' => 'Compte validé, retourne le token JWT',
                    ],
                    '400' => [
                        'description' => 'Token invalide ou expiré',
                    ],
                ],
            ),
        ),
        new Post(
            uriTemplate: '/users/forgot-password/{token}',
            uriVariables: [
                'token' => 'string',
            ],
            security: "is_granted('PUBLIC_ACCESS')",
            input: ResetPasswordDto::class,
            output: TokenDto::class,
            provider: ResetPasswordUserItemProvider::class,
            processor: ResetPasswordUserProcessor::class,
            openapi: new Operation(
                summary: 'Réinitialiser le mot de passe',
                description: 'Définit un nouveau mot de passe via le token de réinitialisation.',
                tags: ['Authentification'],
                requestBody: new RequestBody(
                    description: 'Nouveau mot de passe',
                    required: true,
                ),
                responses: [
                    '200' => [
                        'description' => 'Mot de passe réinitialisé, retourne le token JWT',
                    ],
                    '400' => [
                        'description' => 'Token invalide ou mot de passe trop faible',
                    ],
                ],
            ),
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
            openapi: new Operation(
                summary: 'Mettre à jour le profil',
                description: 'Met à jour les informations du profil utilisateur (pseudo, email, image).',
                tags: ['Authentification'],
                requestBody: new RequestBody(
                    description: 'Données du profil à mettre à jour',
                    required: true,
                ),
                responses: [
                    '200' => [
                        'description' => 'Profil mis à jour, retourne le nouveau token JWT',
                    ],
                    '400' => [
                        'description' => 'Données invalides',
                    ],
                    '409' => [
                        'description' => 'Email ou pseudo déjà utilisé',
                    ],
                ],
            ),
        ),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
            security: "is_granted('PUBLIC_ACCESS')",
            input: CreateUserDto::class,
            processor: CreateUserVisitorProcessor::class,
            openapi: new Operation(
                summary: 'Créer un compte',
                description: 'Crée un nouveau compte utilisateur. Un email de validation sera envoyé.',
                tags: ['Authentification'],
                requestBody: new RequestBody(
                    description: 'Informations du nouveau compte',
                    required: true,
                ),
                responses: [
                    '201' => [
                        'description' => 'Compte créé, email de validation envoyé',
                    ],
                    '400' => [
                        'description' => 'Données invalides ou mot de passe trop faible',
                    ],
                    '409' => [
                        'description' => 'Email ou pseudo déjà utilisé',
                    ],
                ],
            ),
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
            openapi: new Operation(
                summary: 'Supprimer le compte',
                description: 'Supprime définitivement le compte utilisateur et toutes ses données.',
                tags: ['Authentification'],
                responses: [
                    '204' => [
                        'description' => 'Compte supprimé avec succès',
                    ],
                    '403' => [
                        'description' => 'Vous ne pouvez supprimer que votre propre compte',
                    ],
                ],
            ),
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
        #[ApiProperty(
            readable: true,
            writable: false,
            identifier: true,
            description: 'Identifiant unique de l\'utilisateur',
            example: '9a8b7c6d-5e4f-3a2b-1c0d-9e8f7a6b5c4d',
        )]
        public ?string $uuid = null,
        #[Assert\NotBlank]
        #[ApiProperty(
            description: 'Chemin relatif vers l\'image de profil',
            example: '/uploads/users/avatar.png',
        )]
        public ?string $image = null,
        #[Assert\NotBlank]
        #[ApiProperty(
            description: 'Pseudonyme de l\'utilisateur',
            example: 'PiloteF1Fan',
        )]
        public ?string $pseudo = null,
        #[Assert\NotBlank]
        #[Assert\Email]
        #[ApiProperty(
            description: 'Adresse email de l\'utilisateur',
            example: 'pilote@example.com',
        )]
        public ?string $email = null,
        #[ApiProperty(
            readableLink: true,
            writableLink: false,
            description: 'Cosmétique de voiture équipé',
        )]
        public ?CosmeticPossessedResource $carCosmetic = null,
        #[ApiProperty(
            readableLink: true,
            writableLink: false,
            description: 'Cosmétique de casque équipé',
        )]
        public ?CosmeticPossessedResource $helmetCosmetic = null,
        #[ApiProperty(
            readableLink: true,
            writableLink: false,
            security: "is_granted('IS_AUTHENTICATED_FULLY') && object.uuid == user.getUuid()",
            description: 'Portefeuille de crédits (visible uniquement par le propriétaire)',
        )]
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
