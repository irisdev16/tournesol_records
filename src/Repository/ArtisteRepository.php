<?php

namespace App\Repository;

use App\Entity\Artiste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Artiste>
 */
class ArtisteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artiste::class);
    }

    public function findBySearchInTitle(string $search) : array{

        $queryBuilder = $this->createQueryBuilder('artist');

        //dd($queryBuilder);

        $query = $queryBuilder -> select('artist')
            ->where('artist.alias LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery();

        return $query->getResult();
    }


}