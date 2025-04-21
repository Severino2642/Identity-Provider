<?php

namespace App\Repository;

use App\Entity\MailHistory;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MailHistory>
 */
class MailHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailHistory::class);
    }

    public function findByLastEmail($email): ?MailHistory
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.mail = :email')
            ->addOrderBy('m.modification_date', 'DESC')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function save(MailHistory $mailHistory)
    {
        $this->getEntityManager()->persist($mailHistory);
        $this->getEntityManager()->flush();
    }

    public function remove(MailHistory $item)
    {
        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
    }
}
