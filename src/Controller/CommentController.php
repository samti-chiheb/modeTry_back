<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use App\Entity\Users;
use App\Service\JWTService;
use App\Entity\Comment;

class CommentController extends AbstractController
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

    #[Route('/comment/create', name: 'create_comment', methods: ['POST'])]
    public function createComment(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $user = $this->getUserFromJwt($request, $entityManager);
            $data = json_decode($request->getContent(), true);

            $postId = $data['postId'];
            $content = $data['content'];

            $post = $entityManager->getRepository(Post::class)->find($postId);
            if (!$post) {
                return $this->json(['error' => 'Post non trouvé.'], JsonResponse::HTTP_NOT_FOUND);
            }

            $comment = new Comment();
            $comment->setUser($user)
                ->setPost($post)
                ->setContent(
                    $content
                )
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());


            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->json(['message' => 'Commentaire créé avec succès.']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/comment/update/{id}', name: 'update_comment', methods: ['PUT'])]
    public function updateComment(Request $request, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        try {
            $user = $this->getUserFromJwt($request, $entityManager);
            $comment = $entityManager->getRepository(Comment::class)->find($id);

            if (!$comment) {
                return $this->json(['error' => 'Commentaire non trouvé.'], JsonResponse::HTTP_NOT_FOUND);
            }

            if ($comment->getUser()->getId() !== $user->getId()) {
                return $this->json(['error' => 'Modification non autorisée.'], JsonResponse::HTTP_FORBIDDEN);
            }

            $data = json_decode($request->getContent(), true);
            $content = $data['content'] ?? '';

            if (empty($content)) {
                return $this->json(['error' => 'Le contenu ne peut pas être vide.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $comment->setContent($content);
            $comment->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();

            return $this->json(['message' => 'Commentaire mis à jour avec succès.']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/comment/delete/{id}', name: 'delete_comment', methods: ['DELETE'])]
    public function deleteComment(Request $request, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        try {
            $user = $this->getUserFromJwt($request, $entityManager);
            $comment = $entityManager->getRepository(Comment::class)->find($id);

            if (!$comment) {
                return $this->json(['error' => 'Commentaire non trouvé.'], JsonResponse::HTTP_NOT_FOUND);
            }

            // Check if the authenticated user is the author of the comment
            if ($comment->getUser()->getId() !== $user->getId()) {
                return $this->json(['error' => 'Suppression non autorisée.'], JsonResponse::HTTP_FORBIDDEN);
            }

            $entityManager->remove($comment);
            $entityManager->flush();

            return $this->json(['message' => 'Commentaire supprimé avec succès.']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/comments/post/{postId}', name: 'get_comments_for_post', methods: ['GET'])]
    public function getCommentsForPost(Request $request, EntityManagerInterface $entityManager, $postId): JsonResponse
    {
        try {
            // Optional: Authenticate user if needed
            // $user = $this->getUserFromJwt($request, $entityManager);

            $comments = $entityManager->getRepository(Comment::class)->findBy(['post' => $postId]);

            $responseData = array_map(function ($comment) {
                return [
                    'id' => $comment->getId(),
                    'content' => $comment->getContent(),
                    'postId' => $comment->getPost()->getId(),
                    'username' => $comment->getUser()->getUsername(),
                    'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
                    'updatedAt' => $comment->getUpdatedAt()->format('Y-m-d H:i:s')
                ];
            }, $comments);

            return $this->json(['comments' => $responseData]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
