<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface; // Assurez-vous que ceci est correctement importé

class UsersRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Users::class);
        $this->manager = $manager;
    }

    public function createUsers($email, $password, $username, $profilePicture)
    {
        $newUser = new Users();

        $newUser
            ->setEmail($email)
            ->setPassword($password) // Assume password is already hashed or will be hashed
            ->setUsername($username)
            ->setProfilePicture($profilePicture)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());

        $this->manager->persist($newUser);
        $this->manager->flush();
    }

    public function updateUsers(Users $user)
    {
        $user->setUpdatedAt(new \DateTimeImmutable());
        $this->manager->flush();
    }

    public function deleteUsers(Users $user)
    {
        $this->manager->remove($user);
        $this->manager->flush();
    }

    /**
     * Find a User by email.
     * 
     * @param string $email The email to search for.
     * 
     * @return Users|null Returns a Users object or null.
     */
    public function findOneByEmail(string $email): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
