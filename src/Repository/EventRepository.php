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
        private $status1 = 1;
        private $status2 = 2;
        private $status3 = 3;
        private $status4 = 4;
        private $status5 = 5;
        private $status6 = 6;
        private $status7 = 7;

    /**
     * @return Event[]
     */
    public function findEventBySite($site)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.site = :site')
            ->setParameter('site', $site)
            ->andWhere('e.status = :status2 OR e.status = :status3 OR e.status = :status4')
            ->setParameter('status2',$this->status2)
            ->setParameter('status3',$this->status3)
            ->setParameter('status4',$this->status4)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllEventExceptStatusArchivedAndCreated()
    {
        return $this->createQueryBuilder('e')
            ->where('e.status != :archived')
            ->andWhere('e.status != :created')
            ->setParameter('archived', $this->status7)
            ->setParameter('created', $this->status1)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Event[]
     */
    public function findEventByFormParameters(
        $site,
        $dateStart,
        $dateDeadline,
        $organizer,
        $registered,
        $unregistered,
        $finished,
        $id,
        $user
    ) {
        $qb = $this->createQueryBuilder('e');

        if ($site != '4') {
            $qb
                ->andWhere('e.site = :site')
                ->setParameter('site', $site);
        }

        if (!empty($dateStart)) {
            $qb
                ->andWhere('e.startingDateTime < :dateStart')
                ->setParameter('dateStart', $dateStart);
        }

        if (!empty($dateDeadline)) {
            $qb
                ->andWhere('e.inscriptionDeadLine < :dateDeadline')
                ->setParameter('dateDeadline', $dateDeadline);
        }

        if (!empty($organizer)) {
            $qb
                ->andWhere('e.organizer = :id')
                ->setParameter('id', $id);
        }


        if (!empty($registered)) {
            $qb
                ->innerJoin('e.registeredMembers', 'r')
                ->andWhere('r.id = :id')
                ->setParameter('id', $id);
        }

        if (!empty($finished)) {

            $qb
                ->andWhere('e.status = :status5')
                ->setParameter('status5', $this->status5);
        } else {

            $qb
                ->andWhere('e.status = :status2 OR e.status = :status3 OR e.status = :status4')
                ->setParameter('status2',$this->status2)
                ->setParameter('status3',$this->status3)
                ->setParameter('status4',$this->status4);
        }

        $qb->orderBy('e.startingDateTime', 'ASC');

        $results = $qb->getQuery()
            ->getResult();

        if (!empty($unregistered)) {
            $resultsfiltered = [];
            foreach ($results as $result) {
                if (!$result->getRegistered()->contains($user)) {
                    $resultsfiltered[] = $result;
                };
            }
            $results = $resultsfiltered;
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

    //Public Event: opened or ongoing
    public function findPublicEvent($idOrganizer){
        return $this->createQueryBuilder('e')
            ->andWhere('e.organizer = :val')
            ->andWhere('e.status = 2 OR e.status = 3 ')
            ->setParameter('val', $idOrganizer)
            ->getQuery()
            ->getResult();
    }

}
