<?php

namespace App\Repository;

use App\Entity\Attempt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Attempt>
 */
class AttemptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attempt::class);
    }

    public function findByIdUser($idUser): ?Attempt
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.idUser = :iduser')
            ->setParameter('iduser', $idUser)
            ->addOrderBy('a.add_date', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function save(Attempt $item)
    {
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
    }

    public function remove(Attempt $item)
    {
        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
    }

    public function deleteByIdUser($idUser)
    {
        $query = $this->getEntityManager()->createQuery("DELETE FROM App\Entity\Attempt a WHERE a.idUser = ".$idUser);
        $query->execute();
    }
}
