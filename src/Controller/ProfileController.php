<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserTypeForm;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;



final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $this->getUser(),
            'roles' => $this->getUser()->getRoles(),
        ]);
    }
#[Route('/profile/edit', name: 'edit_profile')]
public function editprofile(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
{
    $user = $this->getUser();
    if (!$user) {
        throw $this->createAccessDeniedException();
    }

    $form = $this->createForm(UserTypeForm::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $plainPassword = $form->get('plainPassword')->getData();
        if ($plainPassword) {
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }

        $photoFile = $form->get('photo')->getData();
        if ($photoFile) {
            $newFilename = uniqid().'.'.$photoFile->guessExtension();
            $photoFile->move($this->getParameter('photos_directory'), $newFilename);
            $user->setPhoto($newFilename);
        }

        $em->flush();
        $this->addFlash('success', 'لقد تم تعديل الملف الشخصي بنجاح.');
        return $this->redirectToRoute('edit_profile');
    }

    return $this->render('profile/editprofile.html.twig', [
        'form' => $form->createView(),
    ]);
}

}
