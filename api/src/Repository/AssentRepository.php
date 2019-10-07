<?php

namespace App\Repository;

use App\Entity\Assent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Assent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Assent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Assent[]    findAll()
 * @method Assent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Assent::class);
    }

    // /**
    //  * @return Assent[] Returns an array of Assent objects
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
    public function findOneBySomeField($value): ?Assent
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
