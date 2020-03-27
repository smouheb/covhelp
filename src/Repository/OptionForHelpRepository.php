<?php

namespace App\Repository;

use App\Entity\OptionForHelp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OptionForHelp|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionForHelp|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionForHelp[]    findAll()
 * @method OptionForHelp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionForHelpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionForHelp::class);
    }

    // /**
    //  * @return OptionForHelp[] Returns an array of OptionForHelp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OptionForHelp
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
