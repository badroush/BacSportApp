<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Users;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }
public function findConversation(Users $userA, Users $userB, int $limit = 100): array
    {
        return $this->createQueryBuilder('m')
            ->where('(m.sender   = :a AND m.receiver = :b)')
            ->orWhere('(m.sender = :b AND m.receiver = :a)')
            ->setParameter('a', $userA)
            ->setParameter('b', $userB)
            ->orderBy('m.createdAt', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function markAsRead(Users $me, Users $other): void
{
    $qb = $this->createQueryBuilder('m')
        ->update()
        ->set('m.isRead', ':true')
        ->where('m.sender = :other')
        ->andWhere('m.receiver = :me')
        ->andWhere('m.isRead = :false')
        ->setParameter('true', true)
        ->setParameter('false', false)
        ->setParameter('me', $me)
        ->setParameter('other', $other);
            $qb->getQuery()->execute();
        }

        /**
 * Compte les messages reçus par $user qui ne sont pas encore lus
 */
public function countUnread(Users $user): int
{
    return (int) $this->createQueryBuilder('m')
        ->select('COUNT(m.id)')
        ->where('m.receiver = :me')
        ->andWhere('m.isRead = :false')
        ->setParameter('me', $user)
        ->setParameter('false', false)
        ->getQuery()
        ->getSingleScalarResult();
}


    //    /**
    //     * @return Message[] Returns an array of Message objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Message
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
