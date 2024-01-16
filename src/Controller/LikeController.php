<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Like;
use App\Entity\Post;
use App\Entity\Users;
use App\Service\JWTService;

class LikeController extends AbstractController
{
    private $jwtService;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    private function getUserFromJwt(Request $request, EntityManagerInterface $entityManager): ?Users
    {
        $jwtData = $this->jwtService->getDataFromJWT($request);
        if (isset($jwtData->error)) {
            throw new \Exception('Clé JWT invalide');
        }

        if (!isset($jwtData['id'])) {
            throw new \Exception('Données JWT manquantes.');
        }

        $user = $entityManager->getRepository(Users::class)->find($jwtData['id']);
        if (!$user) {
            throw new \Exception('Utilisateur non trouvé.');
        }

        return $user;
    }

    #[Route('/like/toggle/{postId}', name: 'toggle_like', methods: ['POST'])]
    public function toggleLike(Request $request, EntityManagerInterface $entityManager, $postId): JsonResponse
    {
        try {
            $user = $this->getUserFromJwt($request, $entityManager);
            $post = $entityManager->getRepository(Post::class)->find($postId);

            if (!$post) {
                return $this->json(['error' => 'Post non trouvé.'], JsonResponse::HTTP_NOT_FOUND);
            }

            $like = $entityManager->getRepository(Like::class)->findOneBy([
                'post' => $post,
                'user' => $user
            ]);

            if ($like) {
                // User already liked the post, so remove the like
                $entityManager->remove($like);
                $entityManager->flush();
                return $this->json(['message' => 'Like retiré avec succès.']);
            } else {
                // User hasn't liked the post yet, so add a new like
                $newLike = new Like();
                $newLike->setPost($post)
                    ->setUser($user)
                    ->setCreatedAt(new \DateTimeImmutable());

                $entityManager->persist($newLike);
                $entityManager->flush();
                return $this->json(['message' => 'Like ajouté avec succès.']);
            }
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/likes/count/{postId}', name: 'count_likes', methods: ['GET'])]
    public function countLikes(EntityManagerInterface $entityManager, $postId): JsonResponse
    {
        try {
            // Retrieve the post using the provided ID
            $post = $entityManager->getRepository(Post::class)->find($postId);

            // Check if the post exists
            if (!$post) {
                return $this->json(['error' => 'Post non trouvé.'], JsonResponse::HTTP_NOT_FOUND);
            }

            // Count the likes associated with this post
            $likesCount = $entityManager->getRepository(Like::class)->count(['post' => $post]);

            // Return the count in the response
            return $this->json(['likesCount' => $likesCount]);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }




}
