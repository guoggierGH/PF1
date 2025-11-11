<?php

namespace App\Repository;

use App\Entity\GroupRecommendation;
use App\Entity\Group;
use App\Entity\User;
use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupRecommendation>
 */
class GroupRecommendationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupRecommendation::class);
    }

    /**
     * Obtener recomendaciones de un grupo
     */
    public function findByGroup(Group $group): array
    {
        return $this->createQueryBuilder('gr')
            ->where('gr.group = :group')
            ->setParameter('group', $group)
            ->orderBy('gr.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener recomendaciones recientes de un grupo
     */
    public function findRecentByGroup(Group $group, int $limit = 10): array
    {
        return $this->createQueryBuilder('gr')
            ->where('gr.group = :group')
            ->setParameter('group', $group)
            ->orderBy('gr.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener recomendaciones hechas por un usuario en un grupo
     */
    public function findByUserInGroup(User $user, Group $group): array
    {
        return $this->createQueryBuilder('gr')
            ->where('gr.user = :user')
            ->andWhere('gr.group = :group')
            ->setParameter('user', $user)
            ->setParameter('group', $group)
            ->orderBy('gr.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Verificar si un usuario ya recomendó una película en un grupo
     */
    public function hasUserRecommendedInGroup(User $user, Group $group, Movie $movie): bool
    {
        $result = $this->createQueryBuilder('gr')
            ->select('COUNT(gr.id)')
            ->where('gr.user = :user')
            ->andWhere('gr.group = :group')
            ->andWhere('gr.movie = :movie')
            ->setParameter('user', $user)
            ->setParameter('group', $group)
            ->setParameter('movie', $movie)
            ->getQuery()
            ->getSingleScalarResult();

        return $result > 0;
    }

    /**
     * Obtener películas más recomendadas en un grupo
     */
    public function findMostRecommendedInGroup(Group $group, int $limit = 10): array
    {
        return $this->createQueryBuilder('gr')
            ->select('m.id, m.titulo, COUNT(gr.id) as total')
            ->join('gr.movie', 'm')
            ->where('gr.group = :group')
            ->setParameter('group', $group)
            ->groupBy('m.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener usuarios más activos en un grupo
     */
    public function findMostActiveUsersInGroup(Group $group, int $limit = 10): array
    {
        return $this->createQueryBuilder('gr')
            ->select('u.id, u.nombre, u.apellido, COUNT(gr.id) as total')
            ->join('gr.user', 'u')
            ->where('gr.group = :group')
            ->setParameter('group', $group)
            ->groupBy('u.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Contar recomendaciones en un grupo
     */
    public function countByGroup(Group $group): int
    {
        return $this->createQueryBuilder('gr')
            ->select('COUNT(gr.id)')
            ->where('gr.group = :group')
            ->setParameter('group', $group)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Obtener recomendaciones de películas de un género en un grupo
     */
    public function findByGenreInGroup(Group $group, string $genre): array
    {
        return $this->createQueryBuilder('gr')
            ->join('gr.movie', 'm')
            ->where('gr.group = :group')
            ->andWhere('m.genero = :genre')
            ->setParameter('group', $group)
            ->setParameter('genre', $genre)
            ->orderBy('gr.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener todas las recomendaciones de un usuario
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('gr')
            ->where('gr.user = :user')
            ->setParameter('user', $user)
            ->orderBy('gr.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}