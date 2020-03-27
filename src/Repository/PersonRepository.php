<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    const HELPER = "Helper";

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function peopleThatHelp(string $status = null) : array
    {
        $em = $this->getEntityManager()->getConnection();

        if($status === self::HELPER){

            $sql = "select * from person p 
                    inner join address a on p.id = a.person_id
                    where p.is_helping = 1
                    ";

            $stmt = $em->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        }

        $sql = "select * from person p 
                    inner join address a on p.id = a.person_id
                    where p.is_helping = 0;
                    ";

        $stmt = $em->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();

    }

    public function personMatchingCriterion(object $criterion)
    {
        $walkingDog = null;
        $groceries = null;
        $garbage = null;
        $dryCleaning = null;
        $deliverTakeAway = null;

        if($criterion->get('Walking_Dogs') !== null){
            $walkingDog = " and o.walking_dog = 1";
        }
        if($criterion->get('Groceries') !== null){

            $groceries = " and o.groceries = 1";
        }
        if($criterion->get('Garbage') !== null){
            $garbage=  " and o.garbage = 1";
        }
        if($criterion->get('Dry_Cleaning_pick_up') !== null){
            $dryCleaning = " and o.dry_cleaning = 1";
        }
        if($criterion->get('Deliver_Take_away') !== null ){
            $deliverTakeAway = " and o.deliver_take_away = 1";
        }

        $em = $this->getEntityManager()->getConnection();

        $sql = "select * from person p 
                left join option_for_help o on o.person_id = p.id
                inner join address a on a.person_id = p.id
                 where p.is_helping = 1"
                .$walkingDog
                .$groceries
                .$garbage
                .$dryCleaning
                .$deliverTakeAway;


        $sql = trim($sql);
        $stmt = $em->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // /**
    //  * @return Person[] Returns an array of Person objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Person
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
