<?php

namespace App\Repository;

use App\Entity\UserComments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserComments>
 *
 * @method UserComments|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserComments|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserComments[]    findAll()
 * @method UserComments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserComments::class);
    }

//    /**
//     * @return UserComments[] Returns an array of UserComments objects
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

//    public function findOneBySomeField($value): ?UserComments
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
