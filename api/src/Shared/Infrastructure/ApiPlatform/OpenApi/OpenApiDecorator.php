<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\ApiPlatform\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Info;
use ApiPlatform\OpenApi\Model\Server;
use ApiPlatform\OpenApi\OpenApi;

final readonly class OpenApiDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated,
    ) {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        // Configure API info
        $openApi = $openApi->withInfo(
            (new Info(
                title: 'King of Paddock API',
                version: '1.0.0',
                description: <<<'DESC'
API pour l'application de fantasy league motorsport **King of Paddock**.

## Fonctionnalités

- **Championnats** : Créez ou rejoignez des championnats privés
- **Enchères** : Enchérissez sur vos pilotes et écuries préférés
- **Stratégies** : Optimisez vos choix avec des bonus et stratégies
- **Duels** : Affrontez vos amis en face à face
- **Classements** : Suivez votre progression et celle de vos adversaires

## Authentification

L'API utilise JWT (JSON Web Token) pour l'authentification. Incluez le token dans le header `Authorization` :

```
Authorization: Bearer <votre-token>
```

Obtenez un token via :
- `/api/login_check` (connexion)
- `/api/users/validation/{token}` (validation de compte)
- `/api/users/forgot-password/{token}` (réinitialisation)
DESC
            ))
        );

        // Add servers
        $openApi = $openApi->withServers([
            new Server(url: 'https://api.kingofpaddock.com', description: 'Production'),
            new Server(url: 'https://staging-api.kingofpaddock.com', description: 'Staging'),
            new Server(url: 'https://kop.local', description: 'Développement local'),
        ]);

        // Configure security
        $schemas = $openApi->getComponents()->getSecuritySchemes() ?? [];
        $schemas['bearerAuth'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
            'description' => 'Token JWT obtenu via /api/login_check',
        ]);

        $openApi = $openApi->withComponents(
            $openApi->getComponents()->withSecuritySchemes($schemas)
        );

        // Add global security requirement
        $openApi = $openApi->withSecurity([
            ['bearerAuth' => []],
        ]);

        return $openApi;
    }
}
