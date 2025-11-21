<?php

// src/Controller/AgentDashboardController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/agent')]
class AgentDashboardController extends AbstractController
{
    #[Route('', name: 'agent_dashboard')]
    public function index()
    {
        return $this->render('home/index.html.twig');
    }
}
