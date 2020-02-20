<?php

namespace App\Repository;

use App\Entity\ProfilePictureName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProfilePictureName|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilePictureName|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilePictureName[]    findAll()
 * @method ProfilePictureName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilePictureNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilePictureName::class);
    }

    // /**
    //  * @return ProfilePictureName[] Returns an array of ProfilePictureName objects
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
    public function findOneBySomeField($value): ?ProfilePictureName
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
