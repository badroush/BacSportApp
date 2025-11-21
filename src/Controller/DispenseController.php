<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Eleve;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Dispense;
use App\Entity\EpreuveBac;

final class DispenseController extends AbstractController
{
    #[Route('/dispense', name: 'app_dispense')]
    public function index(): Response
    {
        return $this->render('dispense/index.html.twig', [
            'controller_name' => 'DispenseController',
        ]);
    }

    #[Route('/epreuvebac/etat/{cin}', name: 'changer_etat_eleve', methods: ['POST'])]
public function changerEtat(Request $request, string $cin, EntityManagerInterface $em): Response
{
    $eleve = $em->getRepository(Eleve::class)->find($cin);
    if (!$eleve) {
        $this->addFlash('danger', ' لم يتم العثور على التلميذ.  ❌');
        return $this->redirectToRoute('epreuve_bac_ajouter');
    }
    $existeeprv=$em->getRepository(EpreuveBac::class)->findOneBy(['eleve' => $eleve]);
    // dd($existeeprv);
    $etat = $request->request->get('etat');
    if($etat == null){
        $this->addFlash('تحذير', ' لم يتم تحديد حالة التلميذ.  ❌');
        return $this->redirectToRoute('epreuve_bac_ajouter', ['cin' => $cin]);
    }
    if($existeeprv != null && ($etat == "absent" || $etat == "dispense")){
        $this->addFlash('إنتبه', ' لا يمكنك تغيير حالة التلميذ بسبب وجود اختبارات, يجب حذفها اولا.  ❌');
        return $this->redirectToRoute('epreuve_bac_ajouter', ['cin' => $cin]);

    }
    else{ 
    // Vérifier si une ligne existe déjà pour cet élève
    $dispenseRepo = $em->getRepository(Dispense::class);
    $dispense = $dispenseRepo->findOneBy(['eleve' => $eleve]);

    if (!$dispense) {
        $dispense = new Dispense();
        $dispense->setEleve($eleve);
    }

    $dispense->setState($etat);
    $em->persist($dispense);
    $em->flush();

    $this->addFlash('success', ' لقد تم تغيير حالة الإختبار !  ✅       ');

    return $this->redirectToRoute('epreuve_bac_ajouter', ['cin' => $cin]);
}
}

}
