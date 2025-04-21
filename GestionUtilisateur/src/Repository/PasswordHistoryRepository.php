<?php

namespace App\Repository;

use App\Entity\PasswordHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PasswordHistory>
 */
class PasswordHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordHistory::class);
    }

    public function save(PasswordHistory $item)
    {
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
    }

    public function findByLastPasswordByIdUser($idUser,$pwd): ?PasswordHistory
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.idUser = :iduser')
            ->andWhere('m.password = :pwd')
            ->addOrderBy('m.modification_date', 'DESC')
            ->setParameter('iduser', $idUser)
            ->setParameter('pwd', $pwd)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function remove(PasswordHistory $item)
    {
        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
    }
}
