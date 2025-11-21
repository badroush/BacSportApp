<?php
namespace App\Controller\Admin;

use App\Entity\Users;
use App\Entity\Eleve;
use App\Controller\Admin\UsersCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Centre;
use App\Entity\Lycee;
use App\Entity\Classe;
use App\Entity\NomClasse;
use App\Entity\Epreuve;
use App\Entity\Etablissement;
use App\Entity\EpreuveBac;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use App\Repository\ClasseRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\ActionControle;



#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UsersCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bsportapp');
    }

// src/Controller/Admin/DashboardController.php

#[Route('/api/classes/{lyceeId}', name: 'api_classes_by_lycee', methods: ['GET'])]
    public function getClassesByLycee(int $lyceeId, ClasseRepository $classeRepository): JsonResponse
    {
        $classes = $classeRepository->findBy(['lycee' => $lyceeId]);

        $data = array_map(fn(Classe $classe) => [
            'id' => $classe->getId(),
            'nom' => $classe->getNomclasse(),
        ], $classes);
        return $this->json($data);
    }
    public function configureMenuItems(): iterable
    {
        # rrtour a la page d'accueil
        yield MenuItem::linkToRoute('Retour à l\'accueil', 'fa fa-home', 'app_home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', Users::class);
        yield MenuItem::linkToCrud('Les élèves', 'fas fa-list', Eleve::class);
        yield MenuItem::linkToCrud('Les classe', 'fas fa-list', NomClasse::class);
        yield MenuItem::linkToCrud('Les Centre', 'fas fa-list', Centre::class);
        yield MenuItem::linkToCrud('Les Lycees', 'fas fa-list', Lycee::class);
        yield MenuItem::linkToCrud('Les Classes', 'fas fa-list', Classe::class);
        yield MenuItem::linkToCrud('Les Epreuve', 'fas fa-list', Epreuve::class);
        yield MenuItem::linkToCrud('Les Etablissement', 'fas fa-list', Etablissement::class);
        yield MenuItem::linkToCrud('Les Epreuve du Bac', 'fas fa-list', EpreuveBac::class);
        yield MenuItem::linkToCrud('controler', 'fas fa-list', ActionControle::class); 
    }
}
