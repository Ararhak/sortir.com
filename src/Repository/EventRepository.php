<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Site;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    /**
     * @return Event[]
     */
    public function findEventBySite($site){

        return $this->createQueryBuilder('e')
            ->andWhere('e.site = :site')
            ->setParameter('site', $site)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Event[]
     */
    public function findEventByFormParameters($site, $dateStart, $dateDeadline, $organizer, $registered, $unregistered, $finished, $id, $user){

        $qb = $this->createQueryBuilder('e');

        $qb
            ->andWhere('e.site = :site')
            ->setParameter('site', $site);
        if(!empty($datestart)) {
            $qb
                ->andWhere('e.startingDateTime < :datestart')
                ->setParameter('datestart', $datestart);
        }
        if(!empty($datedeadline)) {
            $qb
                ->andWhere('e.inscriptionDeadLine < :datedeadline')
                ->setParameter('datedeadline', $datedeadline);
        }

        if(!empty($organizer)){
            $qb
                ->andWhere('e.organizer = :id')
                ->setParameter('id',$id);
        }

//        dump('id',$id);
//        die();

        if(!empty($registered)){
            $qb
                ->innerJoin('e.registeredMembers', 'r')
                ->andWhere('r.id = :id')
                ->setParameter('id',$id);
        }
        if(!empty($finished)){
            $qb
                ->andWhere('e.startingDateTime < :now')
                ->setParameter('now', '\'CURRENT_TIMESTAMP()\'');
        }
            $qb->orderBy('e.startingDateTime', 'ASC');

            $results = $qb->getQuery()
            ->getResult()
            ;
        if(!empty($unregistered)){
            $resultsfiltered = [];
            foreach($results as $result){
                if(!$result->getRegistered()->contains($user)){
                    $resultsfiltered[]=$result;
                };
            }
            $results=$resultsfiltered;
        }
        return $results;
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }


    //methode rajouté au cas où on veut de la pagination
    public function findEventByPage($page = 0, $limit = 100)
    {
        $entityManager = $this->getEntityManager();
        $dql = <<<DQL
SELECT s 
FROM App\Entity\Event s
DQL;
        $query = $entityManager->createQuery($dql)
            ->setFirstResult($page * $limit)
            ->setMaxResults($limit);
        $paginator = new Paginator($query, true);

        return $paginator;
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findCreatedEvent($idOrganizer){

        return $this->createQueryBuilder('e')
            ->andWhere('e.organizer = :val')
            ->setParameter('val', $idOrganizer)
            ->getQuery()
            ->getResult();
    }

}
