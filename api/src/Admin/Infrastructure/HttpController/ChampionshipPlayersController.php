<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\HttpController;

use App\Championship\Infrastructure\Doctrine\Entity\Championship;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class ChampionshipPlayersController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route('/admin/api/championship-by-id/{id}/players', name: 'admin_championship_players', methods: ['GET'])]
    public function __invoke(int $id): JsonResponse
    {
        $championship = $this->em->getRepository(Championship::class)->find($id);

        if (null === $championship) {
            return $this->json([]);
        }

        $players = $this->em->getRepository(Player::class)->findBy(['championship' => $championship]);

        $data = array_map(static function (Player $player): array {
            $user = $player->getUser();

            return [
                'id' => $user->getId(),
                'label' => sprintf('%s (%s)', $player->getName(), $user->getEmail()),
            ];
        }, $players);

        return $this->json($data);
    }
}
