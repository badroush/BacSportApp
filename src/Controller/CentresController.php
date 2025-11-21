<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class CentresController extends AbstractController
{
    #[Route('/centres', name: 'app_centres')]
    public function index(): Response
    {
        return $this->render('centres/index.html.twig', [
            'controller_name' => 'CentresController',
        ]);
    }
}
