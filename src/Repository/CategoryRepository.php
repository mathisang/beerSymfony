<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findCat(string $term, int $id): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.beers', 'b')
            ->where('b.id = :id')
            ->setParameter('id', $id)
            ->andWhere('c.term = :term')
            ->setParameter('term', $term)
            ->getQuery()
            ->getResult();
    }

    public function findByTerm(string $term): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.term = :term')
            ->setParameter('term', $term)
            ->getQuery()
            ->getResult()
        ;
    }
}
