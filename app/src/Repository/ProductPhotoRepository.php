<?php

namespace App\Repository;

use App\Entity\ProductPhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|ProductPhoto find($id, $lockMode = null, $lockVersion = null)
 * @method null|ProductPhoto findOneBy(array $criteria, array $orderBy = null)
 * @method ProductPhoto[]    findAll()
 * @method ProductPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductPhotoRepository extends ServiceEntityRepository
{
    use SyncEntities;
    use DeleteEntities;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductPhoto::class);
    }

    // /**
    //  * @return ProductPhoto[] Returns an array of ProductPhoto objects
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
    public function findOneBySomeField($value): ?ProductPhoto
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