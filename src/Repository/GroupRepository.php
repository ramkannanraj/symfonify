<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    /**
     * Find by name
     *
     * @param string $name Group name
     *
     * @return Group[] Returns an array of Group objects
     */
    public function findByName(string $name)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.name = :name')
            ->setParameter('name', $name)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * Find One By Id Joined To User
     *
     * @param integer $id Group Id
     *
     * @return Group[] Returns an array of Group objects
     */
    public function findOneByIdJoinedToUser(int $id)
    {
        return $this->createQueryBuilder('g')
        ->innerJoin('g.users', 'u')
        ->Select('count(g.id)')
        ->andWhere('g.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult();
    }
    
}
