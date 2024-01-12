<?php

namespace App\Repository;

use App\Entity\TryonResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TryonResult>
 *
 * @method TryonResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method TryonResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method TryonResult[]    findAll()
 * @method TryonResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TryonResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TryonResult::class);
    }

//    /**
//     * @return TryonResult[] Returns an array of TryonResult objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TryonResult
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
