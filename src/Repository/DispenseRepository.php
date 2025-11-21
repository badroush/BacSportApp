<?php

namespace App\Repository;

use App\Entity\Dispense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dispense>
 */
class DispenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dispense::class);
    }

public function countByEtablissement($etablissement = null): int
{
    $qb = $this->createQueryBuilder('d')
        ->join('d.eleve', 'e')
        ->join('e.lycee', 'l')
        ->join('l.etablissement', 'etab')
        ->andWhere('d.state = :state')
        ->andWhere('d.eleve IS NOT NULL')
        ->setParameter('state', 'dispense')
        ->select('COUNT(d.id)');

    // ✅ Si un établissement est fourni, on applique le filtre
    if ($etablissement !== null) {
        $qb->andWhere('etab = :etablissement')
        ->setParameter('etablissement', $etablissement);
    }

    return (int) $qb->getQuery()->getSingleScalarResult();
}





    //    /**
    //     * @return Dispense[] Returns an array of Dispense objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Dispense
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
