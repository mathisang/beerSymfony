<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function avgBeerClient()
    {
        return $this->createQueryBuilder('c')
            ->select('AVG(c.number_beer) as numberBeer')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getAgeConso($min, $max)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.age >= ' . $min)
            ->andWhere('c.age <= ' . $max)
            ->getQuery()
            ->getResult()
        ;
    }
}
