<?php

namespace App\Repository;

use App\Entity\Token;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Token>
 */
class TokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }

    public function findByIdUser($idUser): ?Token
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.idUser = :iduser')
            ->setParameter('iduser', $idUser)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findByTokenValue($token): ?Token
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function save(Token $item)
    {
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
    }

    public function remove(Token $item)
    {
        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
    }

    public function deleteByIdUser($idUser)
    {
        $query = $this->getEntityManager()->createQuery("DELETE FROM App\Entity\Token t WHERE t.idUser = :iduser")
            ->setParameter('iduser', $idUser);
        $query->execute();
    }
}
