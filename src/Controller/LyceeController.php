<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Lycee;
use App\Form\LyceeTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LyceeRepository;
use Symfony\Component\Security\Core\Security;


final class LyceeController extends AbstractController
{

     private $lyceeRepository;

    public function __construct(LyceeRepository $lyceeRepository)
    {
        $this->lyceeRepository = $lyceeRepository;
    }
    #[Route('/lycee', name: 'app_lycee')]
    public function index(Request $request, EntityManagerInterface $em, LyceeRepository $lyceeRepository): Response
{
     $user = $this->getUser(); // récupère l'utilisateur connecté
    $lycees = $lyceeRepository->findByUserEtablissement($user);
// dd($lycees);
    return $this->render('lycee/index.html.twig', [
        'lycees' => $lycees,
    ]);
}
}