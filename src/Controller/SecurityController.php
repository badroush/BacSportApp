<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


final class SecurityController extends AbstractController
{

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        $user = $token->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles())) {

            return new RedirectResponse('/');
        } else {
            return new RedirectResponse('/');
        }
    }

    #[Route('/login', name: 'login')]
public function login(AuthenticationUtils $authenticationUtils): Response
{
    return $this->render('security/login.html.twig', [
        'last_username' => $authenticationUtils->getLastUsername(),
        'error' => $authenticationUtils->getLastAuthenticationError(),
    ]);
}
    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return new RedirectResponse('/login');
        // The logout route is handled by Symfony's security system, so this method can be empty.
        // You can also redirect to a specific page if needed.

    
}
}