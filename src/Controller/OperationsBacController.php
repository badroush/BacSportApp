<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Eleve;
use App\Entity\EpreuveBac;
use App\Entity\OperationBac;
use App\Form\OperationBacTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Bareme;
use App\Repository\BaremeRepository;
use App\Entity\Epreuve;
use App\Repository\EpreuveRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\EpreuveBacTypeForm;
use App\Entity\Dispense;
use App\Repository\DispenseRepository;
final class OperationsBacController extends AbstractController
{
    
  #[Route('/operation-bac', name: 'operation_bac_index')]
    public function index()
    {
        // Affiche la page twig contenant le formulaire
        return $this->render('operations_bac/index.html.twig');
    }

#[Route('/get-epreuves/{cin}', name: 'operation_bac_get_epreuves')]
public function getEpreuves(string $cin, EntityManagerInterface $em): JsonResponse
{
    // Rechercher l'élève par son CIN
    $eleve = $em->getRepository(Eleve::class)->findOneBy(['cin' => $cin]);

    // Si l'élève n'existe pas, on retourne un tableau vide
    if (!$eleve) {
        return new JsonResponse([]);
    }
$dispense = $em->getRepository(Dispense::class)->findOneBy(['eleve' => $eleve]);

    // Récupérer toutes les opérations faites par l'élève
    $operations = $em->getRepository(OperationBac::class)->findBy(['eleve' => $eleve]);
    $epreuvesPasseesIds = array_map(fn($op) => $op->getEpreuve()->getId(), $operations);

    // Récupérer toutes les épreuves assignées à cet élève
    $epreuvesBac = $em->getRepository(EpreuveBac::class)->createQueryBuilder('eb')
        ->join('eb.epreuve', 'e')
        ->where('eb.eleve = :eleve')
        ->orderBy('e.id', 'ASC')
        ->setParameter('eleve', $eleve)
        ->getQuery()
        ->getResult();

    // Déterminer si toutes les épreuves sont passées
    $toutesEpreuvesPassees = count($epreuvesBac) > 0 && count($operations) >= count($epreuvesBac);

    // Construire la réponse JSON
    $data = [];
    if (count($epreuvesBac) > 0) {
        foreach ($epreuvesBac as $epBac) {
            $data[] = [
                'nomeleve' => $eleve->getNomPrenom(),
                'cin' => $eleve->getCin(),
                'sexe' => $eleve->getSexe(),
                'matricule' => $eleve->getMatricule(),
                'lycee' => $eleve->getLycee()?->getNom() ?? 'غير متوفر',
                'classe' => $eleve->getClasse()?->getNomClasse()?->getNom() ?? 'غير متوفر',
                'toutesEpreuvesPassees' => false,
                'id' => $epBac->getEpreuve()->getId(),
                'nom' => $epBac->getEpreuve()->getLibelle(),
            ];
        }
    } else {
        // Si l’élève n’a aucune épreuve, on renvoie quand même ses infos
        $data[] = [
            'nomeleve' => $eleve->getNomPrenom(),
            'cin' => $eleve->getCin(),
            'sexe' => $eleve->getSexe(),
            'matricule' => $eleve->getMatricule(),
            'lycee' => $eleve->getLycee()?->getNom() ?? 'غير متوفر',
            'classe' => $eleve->getClasse()?->getNomClasse()?->getNom() ?? 'غير متوفر',
            'toutesEpreuvesPassees' => true, // puisqu’il n’y a pas d’épreuves à passer
            'disp'=> $dispense?->getState() === 'dispense' ? true : false,
            'id' => null,
            'nom' => null,
        ];
    }

    return new JsonResponse($data);
}
    #[Route('/get-note', name: 'operation_bac_get_note', methods: ['GET'])]
    public function getNote(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $cin = $request->query->get('cin');
        $epreuveId = $request->query->get('epreuve');
        $resultat= $request->query->get('resultat');
        $eleve = $em->getRepository(Eleve::class)->findOneBy(['cin' => $cin]);
        $epreuveBac  = $em->getRepository(Epreuve::class)->find($epreuveId);
        $epreuve = $epreuveBac->getId(); // pour obtenir l'objet Epreuve attendu
        if (!$cin || !$epreuve || $resultat === null) {
            return new JsonResponse(['note' => null]);
        }
        $sexe = $eleve->getSexe();

        $infQuery = $em->createQueryBuilder()
            ->select('b')
            ->from(Bareme::class, 'b')
            ->where('b.epreuve = :speci')
            ->andWhere('b.sex= :sx')
            ->andWhere('b.resultat<:resl')
            ->setParameter('speci',$epreuve)
            ->setParameter('sx', $sexe)
            ->setParameter('resl', $resultat)
            ->getQuery();

        $baremesInf = $infQuery->getResult();
        $max=50;
        $choixInf = 0;
        // dd($baremesInf);
        foreach ($baremesInf as $bareme) {
            // dd($bareme);
            if ($resultat-$bareme->getResultat() < $max) {
                $max = $resultat-$bareme->getResultat();
                $choixInf = $bareme->getResultat();
            }
        }
        // dd($choixInf);
        $supQuery = $em->createQueryBuilder()
            ->select('b')
            ->from(Bareme::class, 'b')
            ->where('b.epreuve = :speci')
            ->andWhere('b.sex= :sx')
            ->andWhere('b.resultat> :resl')
            ->setParameter('speci',$epreuve)
            ->setParameter('sx', $sexe)
            ->setParameter('resl', $resultat)
            ->getQuery();

        $baremesSup = $supQuery->getResult();
        
        $max=50;
        $choixSup= 0;
        foreach ($baremesSup as $bareme) {
            if ($bareme->getResultat()-$resultat < $max) {
                $max = $bareme->getResultat()-$resultat;
                $choixSup = $bareme->getResultat();
            }
        }
        
        $bareme = $em->getRepository(Bareme::class)->findOneBy([
            'epreuve' => $epreuve,
            'sex' => $sexe,
            'resultat' => $resultat,
        ]);
        return new JsonResponse(
            [
                'note' => $bareme?->getNote(),
                'choixInf' => $choixInf,
                'choixSup' => $choixSup,
            ]);
    }

#[Route('/submit-note', name: 'operation_bac_submit_note', methods: ['POST'])]
public function submitNote(Request $request, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    if (!$data) {
        return new JsonResponse([
            'status' => 'error',
            'flash' => ['type' => 'danger', 'message' => 'Données invalides.']
        ], 400);
    }
// dd($data);
    $eleve = $em->getRepository(Eleve::class)->findOneBy(['cin' => $data['cin']]);
    $epreuveId = (int) $data['epreuve'];
    $epreuve = $em->getRepository(Epreuve::class)->find($epreuveId);
    // dd($epreuve);
    if (!$eleve || !$epreuve) {
        return new JsonResponse([
            'status' => 'error',
            'flash' => ['type' => 'خطأ', 'message' => 'تلميذ أو إختبار غير صحيح.']
        ], 404);
    }

    $existing = $em->getRepository(OperationBac::class)->findOneBy([
        'eleve' => $eleve,
        'epreuve' => $epreuve,
    ]);

    if ($existing) {
        return new JsonResponse([
            'status' => 'error',
            'flash' => ['type' => 'تحذير', 'message' => 'لقد تم إضافة هذا الإختبار سابقا.']
        ]);
    }

    $operation = new OperationBac();
    $operation->setEleve($eleve);
    $operation->setEpreuve($epreuve);
    $operation->setResultat((string) $data['resultat']);
    $operation->setNote((string) $data['note']);
    $operation->setPar($this->getUser()?->getId() ?? '2');

    $em->persist($operation);
    $em->flush();

    return new JsonResponse([
        'status' => 'ok',
        // 'flash' => ['type' => 'نجاح', 'message' => 'تم تسجيل الإختبار بنجاح.']
        'msg'=> 'تم تسجيل الإختبار بنجاح.'
    ]);
}

#[Route('/api/operations/{cin}', name: 'api_operations_by_cin', methods: ['GET'])]
public function getOperationsByCin(string $cin, EntityManagerInterface $em): JsonResponse
{
    $eleve = $em->getRepository(Eleve::class)->findOneBy(['cin' => $cin]);

    if (!$eleve) {
        return new JsonResponse(['status' => 'error', 'message' => 'Élève introuvable'], 404);
    }

    $operations = $em->getRepository(OperationBac::class)->findBy(['eleve' => $eleve]);

    $data = [];
    foreach ($operations as $op) {
        $data[] = [
            'epreuve' => $op->getEpreuve()->getLibelle(),
            'resultat' => $op->getResultat(),
            'note' => $op->getNote(),
            'id' => $op->getId(),
        ];
    }

    return new JsonResponse(['status' => 'ok', 'operations' => $data]);
}

#[Route('/delete-operation/{id}', name: 'operation_bac_delete', methods: ['DELETE'])]
public function deleteOperation(OperationBac $operation, EntityManagerInterface $em): JsonResponse
{
    $em->remove($operation);
    $em->flush();

    return new JsonResponse(['status' => 'ok']);
}
#[Route('/api/eleve-dispense/{cin}', name: 'api_eleve_dispense', methods: ['GET'])]
public function getDispenseStatus(string $cin, EntityManagerInterface $em): JsonResponse
{
    $eleve = $em->getRepository(Eleve::class)->findOneBy(['cin' => $cin]);

    if (!$eleve) {
        return new JsonResponse(['status' => 'not_found'], 404);
    }

    $dispense = $em->getRepository(Dispense::class)->findOneBy(['eleve' => $eleve]);
    if ($dispense && $dispense->getState() === 'dispense') {
        return new JsonResponse(['status' => 'dispense']);
    }

    return new JsonResponse(['status' => 'non_dispense']);
}

}