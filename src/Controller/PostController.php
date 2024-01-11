<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use App\Entity\Photo;
use App\Entity\Users;
use App\Service\JWTService;
use App\Service\FileManager;

class PostController extends AbstractController
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

    #[Route('/post/create', name: 'create_post', methods: ['POST'])]
    public function createPost(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {

            $user = $this->getUserFromJwt($request, $entityManager);

            $data = json_decode($request->getContent(), true);

            $photoId = $data['photoId'];
            $description = $data['description'];
            $tags = $data['tags'];
            $visibility = $data['visibility'];

            // Validation de la visibilité
            if (!in_array($visibility, ['public', 'private'])) {
                return $this->json(['error' => 'Visibilité invalide.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $photo = $entityManager->getRepository(Photo::class)->find($photoId);
            if (!$photo) {
                return $this->json(['error' => 'Photo non trouvée.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $post = new Post();
            $post->setUser($user)
                ->setPhoto($photo)
                ->setDescription($description)
                ->setTags($tags)
                ->setVisibility($visibility)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->json(['message' => 'Post créé avec succès.']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/posts', name: 'get_all_posts', methods: ['GET'])]
    public function getAllPosts(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $user = $this->getUserFromJwt($request, $entityManager);

            $posts = $entityManager->getRepository(Post::class)->findVisiblePosts($user);

            $responseData = array_map(function ($post) {
                return [
                    'id' => $post->getId(),
                    'description' => $post->getDescription(),
                    'tags' => $post->getTags(),
                    'username' => $post->getUser()->getUsername(),
                    'photoPath' => $post->getPhoto()->getPath(),
                    'visibility' => $post->getVisibility(),
                    'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
                    'updatedAt' => $post->getUpdatedAt()->format('Y-m-d H:i:s')
                ];
            }, $posts);

            return $this->json(['posts' => $responseData]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/post/{id}', name: 'get_one_post', methods: ['GET'])]
    public function getOnePost(Request $request, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        try {
            $user = $this->getUserFromJwt($request, $entityManager);

            $post = $entityManager->getRepository(Post::class)->findPostForUser($id, $user);

            if (!$post) {
                return $this->json(['error' => 'Post non trouvé ou accès non autorisé.'], JsonResponse::HTTP_NOT_FOUND);
            }

            $responseData = [
                'id' => $post->getId(),
                'description' => $post->getDescription(),
                'tags' => $post->getTags(),
                'username' => $post->getUser()->getUsername(),
                'photoPath' => $post->getPhoto()->getPath(),
                'visibility' => $post->getVisibility(),
                'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $post->getUpdatedAt()->format('Y-m-d H:i:s')
            ];

            return $this->json($responseData);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/post/update/{id}', name: 'update_post', methods: ['PUT'])]
    public function updatePost(Request $request, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        try {
            $user = $this->getUserFromJwt($request, $entityManager);

            $post = $entityManager->getRepository(Post::class)->find($id);
            if (!$post) {
                return $this->json(['error' => 'Post non trouvé.'], JsonResponse::HTTP_NOT_FOUND);
            }

            // Vérifier si l'utilisateur est le créateur du post
            if ($post->getUser()->getId() !== $user->getId()) {
                return $this->json(['error' => 'Modification non autorisée.'], JsonResponse::HTTP_FORBIDDEN);
            }

            $data = json_decode($request->getContent(), true);

            // Mise à jour conditionnelle de chaque champ
            if (isset($data['description'])) {
                $post->setDescription($data['description']);
            }
            if (isset($data['tags'])) {
                $post->setTags($data['tags']);
            }
            if (isset($data['visibility'])) {
                $post->setVisibility($data['visibility']);
            }

            $post->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();

            return $this->json(['message' => 'Post mis à jour avec succès.']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/post/delete/{id}', name: 'delete_post', methods: ['DELETE'])]
    public function deletePost(Request $request, EntityManagerInterface $entityManager, FileManager $fileManager, $id): JsonResponse
    {
        try {
            $user = $this->getUserFromJwt($request, $entityManager);

            $post = $entityManager->getRepository(Post::class)->find($id);
            if (!$post) {
                return $this->json(['error' => 'Post non trouvé.'], JsonResponse::HTTP_NOT_FOUND);
            }

            if ($post->getUser()->getId() !== $user->getId()) {
                return $this->json(['error' => 'Opération non autorisée.'], JsonResponse::HTTP_FORBIDDEN);
            }

            // Supprimer l'image du système de fichiers et de la base de données
            $photo = $post->getPhoto();
            if ($photo) {
                if ($photo->getPath()) {
                    $fileManager->delete($photo->getPath());
                }
                $entityManager->remove($photo);
            }

            // Supprimer le post
            $entityManager->remove($post);
            $entityManager->flush();

            return $this->json(['message' => 'Post et image associée supprimés avec succès.']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
