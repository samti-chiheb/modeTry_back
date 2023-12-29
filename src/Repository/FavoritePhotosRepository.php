<?php

namespace App\Repository;

use App\Entity\FavoritePhotos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FavoritePhotos>
 *
 * @method FavoritePhotos|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoritePhotos|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoritePhotos[]    findAll()
 * @method FavoritePhotos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoritePhotosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoritePhotos::class);
    }

//    /**
//     * @return FavoritePhotos[] Returns an array of FavoritePhotos objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FavoritePhotos
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
