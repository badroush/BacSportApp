<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Eleve;
use App\Entity\EpreuveBac;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EpreuveBacTypeForm;
use App\Repository\EpreuveBacRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Dispense;

final class EpreuveBacController extends AbstractController
{
/*************  ✨ Windsurf Command ⭐  *************/
/**
 * Adds a new "Epreuve Bac" entry for a student identified by CIN.
 *
 * This function handles the creation of a new "Epreuve Bac" for a student, ensuring
 * that the student does not exceed the limit of three epreuves and that the chosen
 * epreuve has not already been assigned to the student. If the student or user does
 * not have the necessary access rights, appropriate error messages are displayed.
 * A form is used to gather and validate the epreuve data.
 *
 * @param string $cin The CIN of the student for whom the epreuve is being added.
 * @param Request $request The current HTTP request.
 * @param EntityManagerInterface $em The entity manager for database operations.
 * 
 * @return Response The response object, either rendering the form view or redirecting
 *                  based on the operation result.
 */

/*******  42cb310a-5daa-49ba-a368-fcfed31f8dc6  *******/
#[Route('/epreuve-bac/ajouter/{cin}', name: 'epreuve_bac_ajouter')]
public function ajouter(string $cin, Request $request, EntityManagerInterface $em): Response
{
    $eleve = $em->getRepository(Eleve::class)->find($cin);
    $user = $this->getUser();
    $dispense = $em->getRepository(Dispense::class)->findOneBy(['eleve' => $eleve]);
    $state = $dispense ? $dispense->getState() : 'present';
    
    if (!$eleve) {
    $this->addFlash('خطا', 'لا يمكن العثور على التلميذ');
    return $this->redirectToRoute('app_eleves'); 
    }
    if ($eleve->getLycee()->getEtablissement()->getId() !== $user->getEtablissement()->getId() && !$this->isGranted('ROLE_ADMIN')) {
        $this->addFlash('تحذير', 'لا يمكنك التعديل على هذ التلميذ');
          return $this->redirectToRoute('app_eleves'); // ou toute autre page de retour
    }

    $epreuveBac = new EpreuveBac();
    $epreuveBac->setEleve($eleve); // pré-assignation

    $form = $this->createForm(EpreuveBacTypeForm::class, $epreuveBac);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifie si l’épreuve est déjà assignée à cet élève
        $epreuveChoisie = $epreuveBac->getEpreuve();

        $epreuveExistante = $em->getRepository(EpreuveBac::class)->findOneBy([
            'eleve' => $eleve,
            'epreuve' => $epreuveChoisie,
        ]);

// Vérifier s’il a moins de 3 épreuves
        $count = $em->getRepository(EpreuveBac::class)->count(['eleve' => $eleve]);
        // dd($count);
        if ($count >= 3) {
            $this->addFlash('danger', ' لقد تم تجاوز حدود 3 إختبارات.   ❌');
            return $this->redirectToRoute('epreuve_bac_ajouter', ['cin' => $eleve->getCin()]);
        } elseif ($epreuveExistante) {
        $this->addFlash('warning', ' لقد تم اختيار هذه الإختبار من قبل.   ❌');
        return $this->redirectToRoute('epreuve_bac_ajouter', ['cin' => $eleve->getCin()]);
    } else {
            $em->persist($epreuveBac);
            $em->flush();
            $this->addFlash('success', ' لقد تم تسجيل الإختبار بنجاح.   ✅');
            return $this->redirectToRoute('epreuve_bac_ajouter', ['cin' => $eleve->getCin()]);
    } 
    
    }
    $epreuvesExistantes = $em->getRepository(EpreuveBac::class)->findBy(['eleve' => $eleve]);
    // dd($epreuvesExistantes);
    return $this->render('epreuve_bac/index.html.twig', [
        'form' => $form->createView(),
        'eleve' => $eleve,
        'epreuvesExistantes' => $epreuvesExistantes,
        'state'=>$state,
    ]);
}

#[Route('/epreuve-bac/delete/{id}', name: 'epreuve_bac_delete', methods: ['POST'])]
public function delete(Request $request, EpreuveBac $epreuveBac, EntityManagerInterface $em): RedirectResponse
{
    // Protection CSRF
    if ($this->isCsrfTokenValid('delete_epreuvebac_' . $epreuveBac->getId(), $request->request->get('_token'))) {
        $eleveCin = $epreuveBac->getEleve()->getCin(); // Pour redirection
        $em->remove($epreuveBac);
        $em->flush();

        $this->addFlash('success', ' لقد تم حذف الإختبار بنجاح.  ✅');
        return $this->redirectToRoute('epreuve_bac_ajouter', ['cin' => $eleveCin]);
    }

    $this->addFlash('danger', ' خطأ في حذف الإختبار.  ❌');
    return $this->redirectToRoute('app_eleves');
}
}