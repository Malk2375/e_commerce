<?php

namespace App\Repository;

use App\Entity\SubCategory;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;


/**
 * @extends ServiceEntityRepository<SubCategory>
 */
class SubCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubCategory::class);
    }
    public function findByCategoriesOrderedByAscName(Category $category): array{
        return $this->createQueryBuilder('s')
            ->andWhere('s.category = :val')
            ->setParameter('val', $category)
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findAllOrderedByAscNameQueryBuilder(): ORMQueryBuilder
    {
        return $this->createQueryBuilder('s')->orderBy('s.name', 'ASC');
    }

//    /**
//     * @return SubCategory[] Returns an array of SubCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SubCategory
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
