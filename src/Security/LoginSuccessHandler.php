<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Response; // ⚠️ Cette ligne était manquante
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ActionControle;




class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $router;
    private $em;

    public function __construct(RouterInterface $router, EntityManagerInterface $em)
    {
        $this->router = $router;
        $this->em = $em;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        // Vérifie le flag de construction
        $ctrl = $this->em->getRepository(ActionControle::class)->findOneBy(['actions' => 'under_construction']);

        if ($ctrl && $ctrl->isActive()) {
            return new RedirectResponse($this->router->generate('en_construction'));
        }

        // Sinon, redirige selon rôle
        $user = $token->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('app_home'));
        } elseif (in_array('ROLE_AGENT', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('app_home'));
        } elseif (in_array('ROLE_SOUSAGENT', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('app_home'));
        }

        // Redirection par défaut
        return new RedirectResponse('/');
    }
}
