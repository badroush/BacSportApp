<?php
namespace App\EventListener;

use App\Entity\User;
use App\Entity\ActionControle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
// use App\EventListener\TokenStorageInterface;


class LoginListener
{
    private EntityManagerInterface $em;
    private RouterInterface $router;
    private AuthorizationCheckerInterface $authChecker;

    public function __construct(EntityManagerInterface $em, RouterInterface $router,TokenStorageInterface $tokenStorage,
    AuthorizationCheckerInterface $authChecker)
    {
        $this->em = $em;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authChecker;
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();

        // 1. Mise à jour de la date de connexion
        if ($user instanceof User) {
            $user->setLastLogin(new \DateTime());
            $this->em->persist($user);
            $this->em->flush();
        }

        // 2. Vérifier si site en construction via base de données
        $controle = $this->em->getRepository(ActionControle::class)
            ->findOneBy(['actions' => 'under_construction']);
        // dd($controle, $this->authChecker->isGranted('ROLE_ADMIN'),$controle->isActive());

        if ($controle && $controle->isActive() && !$this->authChecker->isGranted('ROLE_ADMIN')) {
            $response = new RedirectResponse($this->router->generate('en_construction'));
            $event->setResponse($response);
}else{
    $response = new RedirectResponse($this->router->generate('app_home'));
    $event->setResponse($response);
}
    }
}
