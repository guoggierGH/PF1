<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    /**
     * Buscar grupos por nombre o descripción
     */
    public function search(string $query): array
    {
        return $this->createQueryBuilder('g')
            ->where('g.nombre LIKE :query')
            ->orWhere('g.descripcion LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('g.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener grupos de un usuario
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.members', 'm')
            ->where('m.id = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('g.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener grupos más populares (con más miembros)
     */
    public function findMostPopular(int $limit = 10): array
    {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.members', 'm')
            ->groupBy('g.id')
            ->orderBy('COUNT(m.id)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener grupos recientes
     */
    public function findRecent(int $limit = 10): array
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Verificar si un usuario pertenece a un grupo
     */
    public function isUserMember(Group $group, User $user): bool
    {
        $result = $this->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->innerJoin('g.members', 'm')
            ->where('g.id = :groupId')
            ->andWhere('m.id = :userId')
            ->setParameter('groupId', $group->getId())
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();

        return $result > 0;
    }

    /**
     * Obtener grupos disponibles para un usuario (grupos a los que no pertenece)
     */
    public function findAvailableForUser(User $user): array
    {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.members', 'm', 'WITH', 'm.id = :userId')
            ->where('m.id IS NULL')
            ->setParameter('userId', $user->getId())
            ->orderBy('g.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Contar miembros de un grupo
     */
    public function countMembers(Group $group): int
    {
        return $this->createQueryBuilder('g')
            ->select('COUNT(m.id)')
            ->leftJoin('g.members', 'm')
            ->where('g.id = :groupId')
            ->setParameter('groupId', $group->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Obtener grupos con más actividad (más recomendaciones)
     */
    public function findMostActive(int $limit = 10): array
    {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.recommendations', 'r')
            ->groupBy('g.id')
            ->having('COUNT(r.id) > 0')
            ->orderBy('COUNT(r.id)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}