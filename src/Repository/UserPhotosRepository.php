<?php

namespace App\Repository;

use App\Entity\UserPhotos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserPhotos>
 *
 * @method UserPhotos|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPhotos|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPhotos[]    findAll()
 * @method UserPhotos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPhotosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPhotos::class);
    }

//    /**
//     * @return UserPhotos[] Returns an array of UserPhotos objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserPhotos
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
