<?php

namespace App\Controller;

// Entity
use App\Entity\Users;
use App\Entity\Photo;

// Service
use App\Service\JWTService;
use App\Service\FileManager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;



class UserController extends AbstractController
{

    private $jwtService;

    public function __construct(JWTService $jwtService, FileManager $fileManager)
    {
        $this->jwtService = $jwtService;
        $this->fileManager = $fileManager;
    }


    #[Route('/registration', name: 'app_registration', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        RequestStack $requestStack
    ): JsonResponse {

        try {
            $jwtData = $this->jwtService->getDataFromJWT($request);

            if (isset($jwtData->error)) {
                return $this->json(['error' => 'clé jwt invalide'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $existingUser = $entityManager->getRepository(Users::class)->findOneByEmail($jwtData['email']);

            if ($existingUser) {
                return $this->json(['error' => 'Un utilisateur avec cet e-mail existe déjà.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $users = new Users();
            $users->setUsername($jwtData['username'] ?? null);
            $users->setEmail($jwtData['email'] ?? null);
            $users->setPassword(isset($jwtData['password']) ? $passwordHasher->hashPassword($users, $jwtData['password']) : null);
            $users->setSize($jwtData['size'] ?? null);
            $users->setHeight($jwtData['height'] ?? null);
            $photoId = $jwtData['photoId'] ?? null;

            if ($photoId) {
                $photo = $entityManager->getRepository(Photo::class)->find($photoId);

                if ($photo) {
                    $users->setPhoto($photo);
                } else {
                    return $this->json(['error' => 'Photo introuvable.'], JsonResponse::HTTP_BAD_REQUEST);
                }
            }

            $users->setCreatedAt(new \DateTimeImmutable());
            $users->setUpdatedAt(new \DateTimeImmutable());

            $errors = $validator->validate($users);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
                return $this->json([
                    'message' => 'Erreur lors de l\'inscription',
                    'errors' => $errorMessages,
                ], JsonResponse::HTTP_BAD_REQUEST);
            }

            $entityManager->persist($users);
            $entityManager->flush();
        } catch (JWTDecodeFailureException $e) {
            return $this->json([
                'message' => 'Une erreur est survenue lors de la décodage du token.',
                'error' => $e->getMessage(),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => 'Utilisateur enregistré avec succès',
            'userId' => $users->getId(),
        ]);
    }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        JWTTokenManagerInterface $JWTManager,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $jwtData = $this->jwtService->getDataFromJWT($request);

        if (isset($jwtData->error)) {
            return $this->json(['error' => 'clé jwt invalide'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $email = $jwtData['email'] ?? '';
        $password = $jwtData['password'] ?? ''; // Assurez-vous que cela est sécurisé et faisable

        $user = $entityManager->getRepository(Users::class)->findOneByEmail($email);

        if (!$user || !$passwordEncoder->isPasswordValid($user, $password)) {
            return $this->json(['error' => "L'e-mail ou le mot de passe est erroné"], Response::HTTP_UNAUTHORIZED);
        }

        $token = $JWTManager->create($user); // créez le token de connection

        $photoUrl = null;

        if ($user->getPhoto()) {
            $photoUrl = $user->getPhoto()->getPath();
        }

        return $this->json([
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'size' => $user->getSize(),
                'height' => $user->getHeight(),
                'profilePicture' => $photoUrl
            ],
        ]);
    }

    #[Route('/user/update', name: 'update_user', methods: ['PATCH'])]
    public function updateUser(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder
    ): Response {

        $jwtData = $this->jwtService->getDataFromJWT($request);
        if (isset($jwtData->error)) {
            return $this->json(['error' => 'clé jwt invalide'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($jwtData['id'])) {
            return $this->json(['error' => 'ID utilisateur non fourni.'], Response::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(Users::class)->find($jwtData['id']);
        if (!$user) {
            return $this->json(['error' => "Utilisateur non trouvé."], Response::HTTP_NOT_FOUND);
        }


        // Mettre à jour les champs si présents dans le JWT

        // Initialiser une liste pour suivre les champs mis à jour
        $updatedFields = [];

        // Mise à jour du mot de passe, si fourni
        if (isset($jwtData['password'])) {
            $newEncodedPassword = $passwordEncoder->hashPassword($user, $jwtData['password']);
            $user->setPassword($newEncodedPassword);
            $updatedFields[] = 'mot de passe';
        }

        // Mise à jour de l'email, après vérification de sa non-existence chez un autre utilisateur
        if (isset($jwtData['email'])) {
            $existingUser = $entityManager->getRepository(Users::class)->findOneByEmail($jwtData['email']);

            if ($existingUser && $existingUser->getId() !== $user->getId()) {
                return $this->json(['error' => 'Cet email est déjà utilisé par un autre compte.'], Response::HTTP_BAD_REQUEST);
            }

            $user->setEmail($jwtData['email']);
            $updatedFields[] = 'email';
        }

        // Si un username est fourni, mettez à jour le username
        if (isset($jwtData['username'])) {
            $user->setUsername($jwtData['username']);
            $updatedFields[] = 'username';
        }

        // Si une taille (size) est fournie, mettez à jour la taille
        if (isset($jwtData['size'])) {
            $user->setSize($jwtData['size']);
            $updatedFields[] = 'taille';
        }

        // Si une hauteur (height) est fournie, mettez à jour la hauteur
        if (isset($jwtData['height'])) {
            $user->setHeight($jwtData['height']);
            $updatedFields[] = 'hauteur';
        }

        // Si une photo de profil est fournie, mettez à jour la photo de profil
        if (isset($jwtData['photoId'])) {
            $photoId = $jwtData['photoId'] ?? null;
            if ($photoId) {
                $photo = $entityManager->getRepository(Photo::class)->find($photoId);

                if ($photo) {
                    $users->setPhoto($photo);
                    $updatedFields[] = 'photo de profile';
                } else {
                    return $this->json(['error' => 'Photo introuvable.'], JsonResponse::HTTP_BAD_REQUEST);
                }
            }
        }

        // Persister les changements
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'Le profil a été mis à jour avec succès!',
            'updatedFields' => $updatedFields
        ], Response::HTTP_OK);
    }


    // #[Route('/user/upload-picture', name: 'upload_profile_picture', methods: ['POST'])]
    // public function uploadProfilePicture(Request $request, EntityManagerInterface $entityManager): JsonResponse
    // {
    //     $jwtData = $this->jwtService->getDataFromJWT($request);
    //     if (isset($jwtData->error)) {
    //         return $this->json(['error' => 'clé jwt invalide'], JsonResponse::HTTP_BAD_REQUEST);
    //     }

    //     // Vérification de l'existence d'une erreur dans les données JWT
    //     if (isset($jwtData['error'])) {
    //         return $this->json(['error' => $jwtData['error']], Response::HTTP_BAD_REQUEST);
    //     }

    //     // Vérification des informations nécessaires dans les données JWT
    //     if (!isset($jwtData['id'])) {
    //         return $this->json(['error' => 'Données manquantes pour l\'identification ou les paramètres de la photo.'], Response::HTTP_BAD_REQUEST);
    //     }

    //     // Validation de l'utilisateur
    //     $user = $entityManager->getRepository(Users::class)->find($jwtData['id']);
    //     if (!$user) {
    //         return $this->json(['error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
    //     }

    //     $file = $request->files->get('profilePicture');

    //     if (!$file) {
    //         return $this->json(['error' => 'Aucun fichier fourni.'], Response::HTTP_BAD_REQUEST);
    //     }

    //     try {
    //         // Utiliser le service FileManager pour stocker la photo et récupérer le chemin
    //         $filePath = $this->fileManager->upload($file);

    //         // Créer et configurer la nouvelle entité Photo
    //         $photo = new Photo();
    //         $photo->setUser($user)
    //             ->setPath($filePath);

    //         // Mise à jour du profilePicture de l'utilisateur
    //         $user->setProfilePicture($filePath);

    //         // Persister la nouvelle photo dans la base de données
    //         $entityManager->persist($photo);
    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->json([
    //             'message' => 'La photo a été téléchargée et enregistrée avec succès!',
    //             'filePath' => $filePath,
    //         ]);
    //     } catch (\Exception $e) {
    //         // Gérer l'exception si quelque chose se passe mal pendant l'upload
    //         return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    // /**
    //  * Required userId and array of photoIds
    //  *
    //  * @param Request $request
    //  * @param EntityManagerInterface $entityManager
    //  * @param RequestStack $requestStack
    //  * @return JsonResponse
    //  */
    // #[Route('/user/delete-pictures', name: 'delete_pictures', methods: ['POST'])]
    // public function deletePictures(Request $request, EntityManagerInterface $entityManager, RequestStack $requestStack): JsonResponse
    // {
    //     $jwtData = $this->jwtService->getDataFromJWT($request);
    //     if (isset($jwtData->error)) {
    //         return $this->json(['error' => 'clé jwt invalide'], JsonResponse::HTTP_BAD_REQUEST);
    //     }
    //     if (isset($jwtData['error'])) {
    //         return $this->json(['error' => $jwtData['error']], Response::HTTP_BAD_REQUEST);
    //     }

    //     // Assurez-vous que 'photoIds' est un tableau d'identifiants
    //     if (!isset($jwtData['userId'], $jwtData['photoIds']) || !is_array($jwtData['photoIds'])) {
    //         return $this->json(['error' => 'Informations utilisateur ou photos manquantes.'], Response::HTTP_BAD_REQUEST);
    //     }

    //     $user = $entityManager->getRepository(Users::class)->find($jwtData['userId']);

    //     if (!$user) {
    //         return $this->json(['error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
    //     }

    //     foreach ($jwtData['photoIds'] as $photoId) {
    //         $photo = $entityManager->getRepository(Photo::class)->find($photoId);

    //         if (!$photo) {
    //             continue; // Si la photo n'existe pas, continuez avec la suivante
    //         }

    //         if ($photo->getUser()->getId() !== $user->getId()) {
    //             continue; // Si la photo n'appartient pas à l'utilisateur, continuez avec la suivante
    //         }

    //         try {
    //             $urlPath = $photo->getPath();
    //             $relativePath = parse_url($urlPath, PHP_URL_PATH);

    //             $projectDir = $this->getParameter('kernel.project_dir');
    //             $filePath = $projectDir . '/public' . $relativePath;

    //             if (file_exists($filePath)) {
    //                 unlink($filePath);
    //             }

    //             if ($photo->getType() === Photo::TYPE_PROFILE) {
    //                 $baseUrl = $requestStack->getCurrentRequest()->getSchemeAndHttpHost();
    //                 $defaultProfilePicPath = $baseUrl . '/defaults/images/profileImage.jpg';
    //                 $user->setProfilePicture($defaultProfilePicPath);
    //                 $entityManager->persist($user);
    //             }

    //             $entityManager->remove($photo);
    //         } catch (\Exception $e) {
    //             continue; // Si une erreur se produit lors de la suppression, continuez avec la photo suivante
    //         }
    //     }

    //     $entityManager->flush(); // Effectuez un seul flush après toutes les suppressions

    //     return $this->json(['message' => 'Les photos ont été supprimées avec succès.']);
    // }


}
