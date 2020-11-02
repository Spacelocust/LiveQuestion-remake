<?php

namespace App\Repository;

use App\Entity\Concern;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Concern|null find($id, $lockMode = null, $lockVersion = null)
 * @method Concern|null findOneBy(array $criteria, array $orderBy = null)
 * @method Concern[]    findAll()
 * @method Concern[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConcernRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Concern::class);
    }

    // /**
    //  * @return Concern[] Returns an array of Concern objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Concern
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
