<?php

namespace App\Repository;

use App\Entity\Classe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Eleve;

/**
 * @extends ServiceEntityRepository<Classe>
 */
class ClasseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Classe::class);
    }

     public function findClassesByUser($user)
{
    if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
        return $this->findAll(); // retourne toutes les classes pour l'admin
    }

    return $this->createQueryBuilder('c') // c pour Classe
        ->join('c.lycee', 'l')             // relation entre Classe et Lycee
        ->join('l.etablissement', 'e')     // relation entre Lycee et Etablissement
        ->andWhere('e.id = :etabId')
        ->setParameter('etabId', $user->getEtablissement()->getId())
        ->orderBy('l.nom', 'ASC')         // tri par nom de la lycee
        ->getQuery()
        ->getResult();
}


public function findByUserLycee($user)
{
    if (in_array('ROLE_ADMIN', $user->getRoles())) {
        return $this->findAll();
    }

    return $this->createQueryBuilder('c') // c pour Classe
        ->join('c.lycee', 'l')
        ->join('l.etablissement', 'e')
        ->andWhere('e.id = :etabId')
        ->setParameter('etabId', $user->getEtablissement()->getId())
        ->getQuery()
        ->getResult();
}


    public function computePercent(Classe $classe): float
    {
        $em = $this->getEntityManager(); // ✅ au lieu de $this->_em    
        $eleves = $em->getRepository(Eleve::class)->findByClasse($classe);
        $total = count($eleves);
    if ($total === 0) {
        return 0;
    }

        // total des épreuves
        $totalExpected = (int) $em->createQueryBuilder()
            ->select('COUNT(eb.id)')
            ->from('App\Entity\EpreuveBac', 'eb')
            ->where('eb.eleve IN (:eleves)')
            ->setParameter('eleves', $eleves)
            ->getQuery()
            ->getSingleScalarResult();

        if ($totalExpected === 0) {
            return 0;
        }
// dd($totalExpected/3, $total);
        // opérations effectuées
        $doneOps = (int) $em->createQueryBuilder()
            ->select('COUNT(ob.id)')
            ->from('App\Entity\OperationBac', 'ob')
            ->where('ob.eleve IN (:eleves)')
            ->setParameter('eleves', $eleves)
            ->getQuery()
            ->getSingleScalarResult();
// dd($doneOps/3,$total);
        // élèves dispensés (optionnel)
        $doneDisp = (int) $em->createQueryBuilder()
            ->select('COUNT(d.id)')
            ->from('App\Entity\Dispense', 'd')
            ->where('d.eleve IN (:eleves)')
            ->andWhere('d.state = :dispense')
            ->setParameter('eleves', $eleves)
            ->setParameter('dispense', 'dispense')
            ->getQuery()
            ->getSingleScalarResult();

        $doneTotal = ($doneOps/3) + $doneDisp;
        // dd($doneOps,$doneDisp, $total);

        return round($doneTotal  / $total, 2);
    }
    public function countByEtablissement($etablissement): int
    {
        return $this->createQueryBuilder('c')
            ->join('c.lycee', 'l')
            ->where('l.etablissement = :etab')
            ->setParameter('etab', $etablissement)
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

}
