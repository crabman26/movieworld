<?php

namespace App\Repository;

use App\Entity\Voted;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Voted|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voted|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voted[]    findAll()
 * @method Voted[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VotedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voted::class);
    }

    // /**
    //  * @return Voted[] Returns an array of Voted objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Voted
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
