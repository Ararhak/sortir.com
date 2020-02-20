<?php

namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Member|null find($id, $lockMode = null, $lockVersion = null)
 * @method Member|null findOneBy(array $criteria, array $orderBy = null)
 * @method Member[]    findAll()
 * @method Member[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }


    public function findByPseudoOrEmail($value){
        return $this->createQueryBuilder('m')
            ->andWhere('m.pseudo = :value')
            ->orWhere('m.mail = :value')
            ->setParameter( 'value', $value )
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByEmail($value){
        return $this->createQueryBuilder('m')
            ->andWhere('m.mail = :value')
            ->setParameter( 'value', $value )
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Member[] Returns an array of Member objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Member
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


}
