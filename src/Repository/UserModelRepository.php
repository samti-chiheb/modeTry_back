<?php

namespace App\Repository;

use App\Entity\UserModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserModel>
 *
 * @method UserModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserModel[]    findAll()
 * @method UserModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserModel::class);
    }

//    /**
//     * @return UserModel[] Returns an array of UserModel objects
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

//    public function findOneBySomeField($value): ?UserModel
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
