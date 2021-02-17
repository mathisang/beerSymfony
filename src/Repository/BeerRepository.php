<?php

namespace App\Repository;

use App\Entity\Beer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Beer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Beer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Beer[]    findAll()
 * @method Beer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Beer::class);
    }

    public function getLastBeers(): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.published_at', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function getBeersCategory(int $category): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.categories', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $category)
            ->orderBy('b.published_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
