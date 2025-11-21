<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Users;
use App\Entity\Message;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\MessageEncryptor;


final class ChatController extends AbstractController
{
     #[Route('/chat', name: 'chat')]
    public function index(MessageRepository $repo,entityManagerInterface $em): JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        // Récupère tous les utilisateurs sauf moi
        $users = $em->getRepository(Users::class)->findAll();

        return $this->render('chat/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/chat/send', name: 'chat_send', methods: ['POST'])]
    public function send(Request $request, EntityManagerInterface $em, UserInterface $user,MessageEncryptor $encryptor): JsonResponse
    {
        $receiverId = $request->request->get('receiver');
        $content = $request->request->get('content');

        $receiver = $em->getRepository(Users::class)->find($receiverId);
// dd($receiverId, $content);
        if (!$receiver || !$content) {
            return new JsonResponse(['status' => 'error'], 400);
        }

        $message = new Message();
        $message->setSender($user);
        $message->setReceiver($receiver);
        $message->setContent($encryptor->encrypt($content));
        $message->setCreatedAt(new \DateTimeImmutable());

        $em->persist($message);
        $em->flush();
// dd($message);
        return new JsonResponse(['status' => 'sent']);
    }

    #[Route('/chat/messages/{id}', name: 'chat_messages')]
    public function messages(Users $id, MessageRepository $repo, UserInterface $user, MessageEncryptor $encryptor): JsonResponse
    {
        $messages = $repo->findConversation($user, $id);
        $repo->markAsRead($user, $id);     
        // dd($messages);
        $data = array_map(function (Message $msg) use ($user, $encryptor) {
            return [
                'fromMe' => $msg->getSender()->getId() === $user->getId(),
                'sender' => $msg->getSender()->getUser(),
                'content' => $encryptor->decrypt($msg->getContent()),
                'createdAt' => $msg->getCreatedAt()->format('Y-m-d H:i'),
            ];
        }, $messages);
        return new JsonResponse($data);
    }

    #[Route('/chat/unread-count', name: 'chat_unread_count')]
public function unreadCount(MessageRepository $repo, UserInterface $user): JsonResponse
{
    return new JsonResponse([
        'count' => $repo->countUnread($user)
    ]);
}

}
