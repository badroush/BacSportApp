<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Lycee;
use App\Entity\EpreuveBac;
use App\Entity\Dispense;
use App\Entity\ActionControle;
use App\Entity\Users;
use App\Repository\OperationBacRepository;
use App\Repository\EpreuveBacRepository;
use App\Repository\DispenseRepository;
use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Repository\LyceeRepository;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        EntityManagerInterface $em,
        EpreuveBacRepository $epreuveBacRepository,
        DispenseRepository $dispenseRepository,
        ClasseRepository $classeRepository,
        EleveRepository $eleveRepository,
        LyceeRepository $lyceeRepo
    ): Response {
        $user = $this->getUser();

        // 🔒 Vérifie si l'utilisateur est connecté
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $role = $user->getRoles();
        $iduser = $user->getEtablissement()->getId();

        $eleves = $em->getRepository(Eleve::class)->findAll();
        $rows = $lyceeRepo->fetchDashboardStats($user);

        foreach ($rows as &$r) {
            $equiv = ($r['nb_eleves'] - ($r['nb_absences'] + $r['nb_dispenses'])) * 3;
            if ($r['nb_classes'] > 0 || $r['nb_eleves'] > 0) {
                $r['rowColor'] = ($equiv == $r['nb_epreuves']) ? '#85f285' : '#e9f502';
            } else {
                $r['rowColor'] = '#fc5d5d';
            }
        }
        unset($r); // éviter effets de bord

        $data = [];
        foreach ($eleves as $eleve) {
            $classe = $eleve->getClasse();
            if (!$classe) continue;

            if (!$this->isGranted('ROLE_ADMIN') &&
                $classe->getLycee()->getEtablissement()->getId() !== $user->getEtablissement()->getId()) {
                continue;
            }

            $nomClasse = $this->isGranted('ROLE_ADMIN')
                ? $classe->getLycee()->getNom() . '/ ' . $classe->getNomClasse()->getNom() . ' (' . $classe->getNum() . ')'
                : $classe->getNomClasse()->getNom() . ' (' . $classe->getNum() . ')';

            if (!isset($data[$nomClasse])) {
                $data[$nomClasse] = ['garcon' => 0, 'fille' => 0];
            }

            if (strtolower($eleve->getSexe()) === 'h') {
                $data[$nomClasse]['garcon']++;
            } else {
                $data[$nomClasse]['fille']++;
            }
        }

        $labels = array_keys($data);
        $garcons = array_column($data, 'garcon');
        $filles = array_column($data, 'fille');

        if (in_array('ROLE_ADMIN', $role)) {
            $nbeleve = $em->getRepository(Eleve::class)->count([]);
            $nbclasse = $em->getRepository(Classe::class)->count([]);
            $nblycee = $em->getRepository(Lycee::class)->count([]);
            $nbepreuve = $em->getRepository(EpreuveBac::class)->count([]);
            $nbdispense = $dispenseRepository->countByEtablissement(null);
            $nomlycee = '';
            $percent = $nbepreuve / (($nbeleve - $nbdispense) * 3) * 100;
        } else {
            $nbeleve = $eleveRepository->countByEtablissement($user->getEtablissement());
            $nbclasse = $classeRepository->countByEtablissement($user->getEtablissement());
            $nblycee = '';
            $nbepreuve = $epreuveBacRepository->countByEtablissement($user->getEtablissement());
            $nbdispense = $dispenseRepository->countByEtablissement($user->getEtablissement());
            $nomlycee = $user->getEtablissement()->getEtablissement();
            $percent = $nbepreuve / (($nbeleve - $nbdispense) * 3) * 100;
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'nbeleve' => $nbeleve,
            'nbclasse' => $nbclasse,
            'nblycee' => $nblycee,
            'nbepreuve' => $nbepreuve,
            'nbdispense' => $nbdispense,
            'nomlycee' => $nomlycee,
            'percent' => $percent,
            'labels' => $labels,
            'garcons' => $garcons,
            'filles' => $filles,
            'stats' => $rows,
        ]);
    }
    #[Route('/en-construction', name: 'en_construction')]
public function maintenance(): Response
{
    return $this->render('maintenance.html.twig');
}

#[Route('/check', name: 'login_redirect')]
public function loginRedirect(EntityManagerInterface $em): Response
{
    $ctrl = $em->getRepository(ActionControle::class)->findOneBy(['actions' => 'under_construction']);

    if ($ctrl && $ctrl->isActive()) {
        return $this->redirectToRoute('en_construction');
    }

    return $this->redirectToRoute('agent_homepage'); // route par défaut
}

}