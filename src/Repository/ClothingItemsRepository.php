<?php

namespace App\Repository;

use App\Entity\ClothingItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClothingItems>
 *
 * @method ClothingItems|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClothingItems|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClothingItems[]    findAll()
 * @method ClothingItems[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClothingItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClothingItems::class);
    }

//    /**
//     * @return ClothingItems[] Returns an array of ClothingItems objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ClothingItems
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
