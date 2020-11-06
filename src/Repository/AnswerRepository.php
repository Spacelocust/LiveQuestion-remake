<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    /**
     * Renvoie un tableaux de réponse décroissant par la date
     * @param Question $question
     * @return int|mixed|string
     */
    public function findAllByDate(Question $question)
    {
        return $this->createQueryBuilder('q')
            ->Where('q.question = :val')
            ->setParameter('val', $question)
            ->orderBy('q.date', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Supprime l'ensemble des réponses de la question
     * @param Question $value
     * @return int|mixed|string
     */
    public function deleteAnswer(Question $value)
    {
        return $this->createQueryBuilder('l')
            ->delete()
            ->where('l.question = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return Answer[] Returns an array of Answer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Answer
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
