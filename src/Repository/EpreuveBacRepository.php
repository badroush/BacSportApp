<?php

namespace App\Repository;

use App\Entity\EpreuveBac;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EpreuveBac>
 */
class EpreuveBacRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EpreuveBac::class);
    }
    public function countByEtablissement($etablissement): int
{
    return $this->createQueryBuilder('e')
        ->join('e.eleve', 'el')
        ->join('el.lycee', 'l')
        ->join('l.etablissement', 'etab')
        ->where('etab = :etablissement')
        ->setParameter('etablissement', $etablissement)
        ->select('COUNT(e.id)')
        ->getQuery()
        ->getSingleScalarResult();
}


    //    /**
    //     * @return EpreuveBac[] Returns an array of EpreuveBac objects
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

    //    public function findOneBySomeField($value): ?EpreuveBac
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
