<?php

namespace App\Repository;

use App\Entity\VirtualTryOns;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VirtualTryOns>
 *
 * @method VirtualTryOns|null find($id, $lockMode = null, $lockVersion = null)
 * @method VirtualTryOns|null findOneBy(array $criteria, array $orderBy = null)
 * @method VirtualTryOns[]    findAll()
 * @method VirtualTryOns[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VirtualTryOnsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VirtualTryOns::class);
    }

//    /**
//     * @return VirtualTryOns[] Returns an array of VirtualTryOns objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VirtualTryOns
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
