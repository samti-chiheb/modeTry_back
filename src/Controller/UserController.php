<?php

namespace App\Controller;

use App\Entity\Users;  // Assurez-vous que c'est la classe correcte
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


class UserController extends AbstractController
{
    #[Route('/registration', name: 'app_registration', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        // Simplement extraire le JWT de l'entête d'autorisation
        $jwtString = str_replace('Bearer ', '', $request->headers->get('Authorization'));

        // Utiliser une configuration non sécurisée - En production, utilisez la vérification de clé appropriée!
        $config = \Lcobucci\JWT\Configuration::forUnsecuredSigner();



        // Extraire le token JWT
        try {
            $token = $config->parser()->parse($jwtString);
            $claims = $token->claims()->all(); // Récupérer toutes les revendications


            $existingUser = $entityManager->getRepository(Users::class)->findOneByEmail($claims['email']);

            if ($existingUser) {
                return $this->json(['error' => 'Un utilisateur avec cet e-mail existe déjà.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $users = new Users();
            $users->setUsername($claims['username'] ?? null);
            $users->setEmail($claims['email'] ?? null);
            $users->setPassword(isset($claims['password']) ? $passwordHasher->hashPassword($users, $claims['password']) : null);
            $users->setProfilePicture($claims['profilePicture'] ?? 'default_image_path.jpg');
            $users->setSize($claims['size'] ?? null);
            $users->setHeight($claims['height'] ?? null);
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
        } catch (UniqueConstraintViolationException $e) {
            return $this->json([
                'message' => 'Une erreur est survenue lors de l\'inscription.',
                'error' => 'L\'email fourni est déjà utilisé par un autre compte.',
            ], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Une erreur est survenue lors de l\'inscription.',
                'error' => 'Une erreur de serveur est survenue.',
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json([
            'message' => 'Utilisateur enregistré avec succès',
            'userId' => $users->getId(),
        ]);
    }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, UserPasswordHasherInterface $passwordEncoder, JWTTokenManagerInterface $JWTManager, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $entityManager->getRepository(Users::class)->findOneByEmail($email);

        if (!$user || !$passwordEncoder->isPasswordValid($user, $password)) {
            return $this->json(['error' => "L'e-mail ou le mot de passe est erroné"], Response::HTTP_UNAUTHORIZED);
        }

        // Ici, utilisez les données que vous voulez inclure dans le token
        $token = $JWTManager->create($user); // créez le token normal

        return $this->json(['token' => $token]);
    }
}
