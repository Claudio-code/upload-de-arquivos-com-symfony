<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param string $filters
     * @param mixed $fields
     */
    public function getProductsByFilters(string $filters, $fields = false)
    {
        $fetchFilters = explode(';', $filters);
        $result = $this->createQueryBuilder('p');

        foreach ($fetchFilters as $filter) {
            $fetchFilter = explode(':', $filter);

            $result->andWhere("p.{$fetchFilter[0]} {$fetchFilter[1]} :{$fetchFilter[0]}")
                ->setParameter($fetchFilter[0], $fetchFilter[2]);
        }

        if (is_string($fields)) {
            $fetchFields = explode(',', $fields);
            $fetchFields = array_map(fn (string $line) => "p.{$line}", $fetchFields);
            $fetchFields = implode(', ', $fetchFields);

            $result->select($fetchFields);
        }

        return $result->getQuery()->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
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
