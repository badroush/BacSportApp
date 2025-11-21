<?php

// src/Controller/AdminDashboardController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminDashboardController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }
}

