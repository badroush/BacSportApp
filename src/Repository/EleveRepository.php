<?php

namespace App\Repository;

use App\Entity\Eleve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Eleve>
 */
class EleveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eleve::class);
    }

    // EleveRepository.php
public function countByEtablissement($etablissement): int
{
    return $this->createQueryBuilder('e')
        ->join('e.lycee', 'l')
        ->where('l.etablissement = :etab')
        ->setParameter('etab', $etablissement)
        ->select('COUNT(e.cin)')
        ->getQuery()
        ->getSingleScalarResult();
}
public function findByClasse($classe): array
{
    return $this->createQueryBuilder('e')
        ->andWhere('e.classe = :classe')
        ->setParameter('classe', $classe)
        ->getQuery()
        ->getResult();
}

    //    /**
    //     * @return Eleve[] Returns an array of Eleve objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Eleve
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
