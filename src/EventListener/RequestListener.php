<?php
namespace App\EventListener;

use App\Entity\ActionControle;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;


class RequestListener
{
    private EntityManagerInterface $em;
    private RouterInterface $router;
    private TokenStorageInterface $tokenStorage;
    private AuthorizationCheckerInterface $authChecker;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $em,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authChecker,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authChecker;
        $this->logger = $logger;
    }

public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
    $uri = $request->getRequestUri();

    if (preg_match('#^/(login|logout|en-construction|_wdt|_profiler)#', $uri)) {
        return;
    }

    $controle = $this->em->getRepository(ActionControle::class)
        ->findOneBy(['actions' => 'under_construction']);

    if (!$controle || !$controle->isActive()) {
        return;
    }

    $token = $this->tokenStorage->getToken();
// dd($token);
    if (!$token || !$token->getUser() || $token->getUser() === 'anon.') {
        $event->setController(function () {
            return new RedirectResponse($this->router->generate('en_construction'));
        });
        return;
    }

    if (!$this->authChecker->isGranted('ROLE_ADMIN')) {
        $event->setController(function () {
            return new RedirectResponse($this->router->generate('en_construction'));
        });
    }
    }
}
