<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Product find($id, $lockMode = null, $lockVersion = null)
 * @method null|Product findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    use FilterTransform;
    use SyncEntities;
    use DeleteEntities;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param mixed $filters
     * @param mixed $limit
     * @param mixed $fields
     */
    public function getProductsByFilters($filters = false, $limit = false, $fields = false): array
    {
        $result = $this->createQueryBuilder('p');

        if (is_string($limit)) {
            $result->setMaxResults($limit);
        }

        if (is_string($filters)) {
            foreach ($this->transformFilters($filters) as $filter) {
                $result->andWhere("p.{$filter[0]} {$filter[1]} :{$filter[0]}")
                    ->setParameter($filter[0], $filter[2])
                ;
            }
        }

        if (is_string($fields)) {
            $fetchFields = $this->transformFields($fields, 'p');
            $result->select($fetchFields);
        }

        return $result->getQuery()->getResult();
    }
}