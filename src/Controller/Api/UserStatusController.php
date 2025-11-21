<?php

namespace App\Controller\Api;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserStatusController extends AbstractController
{
    #[Route('/api/user/status', name: 'check_user_status', methods: ['GET'])]
public function checkUserStatus(Security $security): JsonResponse
{
    // dd($security);
    $user = $security->getUser();
// dd($user);
    if (!$user) {
        return new JsonResponse(['status' => 'unauthenticated'], 401);
    }

    if ($user->getEtat() !== 'actif') {
        return new JsonResponse(['status' => 'freezed'], 403);
    }

    return new JsonResponse(['status' => 'ok']);
 }

}
