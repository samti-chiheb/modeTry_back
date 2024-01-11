<?php

namespace App\Controller;

// Service
use App\Service\JWTService;
use App\Service\FileManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Photo;
use App\Entity\Users;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhotoController extends AbstractController
{
    private $fileManager;
    private $jwtService;

    public function __construct(FileManager $fileManager, JWTService $jwtService)
    {
        $this->fileManager = $fileManager;
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

    #[Route('/photo/upload', name: 'upload_post_photo', methods: ['POST'])]
    public function uploadPhoto(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {

        try {
            $user = $this->getUserFromJwt($request, $entityManager);

            $file = $request->files->get('photo');
            if (!$file) {
                return $this->json(['error' => 'Aucun fichier fourni.'], JsonResponse::HTTP_BAD_REQUEST);
            }


            $filePath = $this->fileManager->upload($file);

            $photo = new Photo();
            $photo->setUser($user)
                ->setPath($filePath)
                ->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($photo);
            $entityManager->flush();

            return $this->json([
                'message' => 'La photo du post a été téléchargée et enregistrée avec succès!',
                'photoId' => $photo->getId(),
                'filePath' => $filePath
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/photo/delete/{photoId}', name: 'delete_photo', methods: ['DELETE'])]
    public function deletePhoto(Request $request, int $photoId, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $user = $this->getUserFromJwt($request, $entityManager);

            $photo = $entityManager->getRepository(Photo::class)->find($photoId);
            if (!$photo) {
                return $this->json(['error' => 'Photo non trouvée.'], JsonResponse::HTTP_NOT_FOUND);
            }

            if ($photo->getUser()->getId() !== $user->getId()) {
                return $this->json(['error' => 'Opération non autorisée.'], JsonResponse::HTTP_FORBIDDEN);
            }

            if (!$this->fileManager->delete($photo->getPath())) {
                return $this->json(['error' => 'Erreur lors de la suppression du fichier.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            $entityManager->remove($photo);
            $entityManager->flush();

            return $this->json(['message' => 'Photo supprimée avec succès.']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
