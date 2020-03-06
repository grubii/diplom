<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;

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
     * @param $name
     * @return Query
     */
    public function findLike($name)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :val')
            ->setParameter('val', '%' . $name . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $category
     * @return Query
     */
    public function findByCategory($category)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.category = :val')
            ->setParameter('val', $category)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $name
     * @param $category
     * @return Query
     */
    public function findByNameAndCategory($name, $category)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :name')
            ->andWhere('p.category = :category')
            ->setParameter('name', '%' . $name . '%')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

    public function findOneBySomeField($value): ?Product
    {
        try {
            return $this->createQueryBuilder('p')
                ->andWhere('p.name = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }
}
