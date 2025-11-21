<?php

namespace App\Repository;

use App\Entity\Lycee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Users;

/**
 * @extends ServiceEntityRepository<Lycee>
 */
class LyceeRepository extends ServiceEntityRepository
{
                public function __construct(ManagerRegistry $registry)
                {
                    parent::__construct($registry, Lycee::class);
                }
            public function findByUserEtablissement($user)
            {
                if (in_array('ROLE_ADMIN', $user->getRoles())) {
                    return $this->findAll();
                }

                return $this->createQueryBuilder('l')
                    ->join('l.etablissement', 'e')
                    ->andWhere('e.id = :etabId')
                    ->setParameter('etabId', $user->getEtablissement()->getId())
                    ->getQuery()
                    ->getResult();
            }

            public function fetchDashboardStats(Users $user): array
{
    $qb = $this->createQueryBuilder('l')
    ->select([
        'l.id AS id',
        'l.nom AS lycee',
        'l.dateEpreuve AS dateEpreuve',
        'c.centre AS centre',
        'e1.id AS etablissement',

        // Sous‑requête pour les classes
        "(SELECT COUNT(cl2.id)
        FROM App\Entity\Classe cl2
        WHERE cl2.lycee = l) AS nb_classes",

        // Sous‑requête pour les élèves
        "(SELECT COUNT(e2.cin)
        FROM App\Entity\Eleve e2
        WHERE e2.lycee = l) AS nb_eleves",

        // Sous‑requête pour dispenses
        "(SELECT COUNT(d2.id)
        FROM App\Entity\Dispense d2
        JOIN d2.eleve e3
        WHERE e3.lycee = l
            AND d2.state = 'dispense') AS nb_dispenses",

        // Sous‑requête pour absents
        "(SELECT COUNT(d3.id)
        FROM App\Entity\Dispense d3
        JOIN d3.eleve e4
        WHERE e4.lycee = l
            AND d3.state = 'absent') AS nb_absences",

        // Sous‑requête pour épreuves
        "(SELECT COUNT(DISTINCT eb2.id)
        FROM App\Entity\EpreuveBac eb2
        JOIN eb2.eleve e5
        WHERE e5.lycee = l) AS nb_epreuves",
    ])
    ->leftJoin('l.centre', 'c')
    ->leftJoin('l.etablissement', 'e1');


    // ✅ si utilisateur n’est pas admin, on filtre sur son établissement
    if (!in_array('ROLE_ADMIN', $user->getRoles())) {
        $qb->andWhere('e1.id = :etabId')
        ->setParameter('etabId', $user->getEtablissement()->getId());
    }

    $qb->groupBy('l.id, c.centre, e1.id')
    ->orderBy('l.dateEpreuve', 'DESC')
    ->addOrderBy('l.nom', 'ASC');

    return $qb->getQuery()->getArrayResult();
}

        }