<?php

namespace App\Repository;

use App\Entity\Recommendation;
use App\Entity\User;
use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recommendation>
 */
class RecommendationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recommendation::class);
    }

    /**
     * Obtener recomendaciones recibidas por un usuario
     */
    public function findReceivedByUser(User $user, bool $onlyUnread = false): array
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.toUser = :user')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC');

        if ($onlyUnread) {
            $qb->andWhere('r.visto = :visto')
               ->setParameter('visto', false);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Obtener recomendaciones enviadas por un usuario
     */
    public function findSentByUser(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.fromUser = :user')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Contar recomendaciones no leídas de un usuario
     */
    public function countUnreadByUser(User $user): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.toUser = :user')
            ->andWhere('r.visto = :visto')
            ->setParameter('user', $user)
            ->setParameter('visto', false)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Marcar recomendaciones como leídas
     */
    public function markAsRead(User $user): void
    {
        $this->createQueryBuilder('r')
            ->update()
            ->set('r.visto', ':visto')
            ->where('r.toUser = :user')
            ->setParameter('visto', true)
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }

    /**
     * Verificar si un usuario ya recomendó una película a otro usuario
     */
    public function hasRecommended(User $fromUser, User $toUser, Movie $movie): bool
    {
        $result = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.fromUser = :fromUser')
            ->andWhere('r.toUser = :toUser')
            ->andWhere('r.movie = :movie')
            ->setParameter('fromUser', $fromUser)
            ->setParameter('toUser', $toUser)
            ->setParameter('movie', $movie)
            ->getQuery()
            ->getSingleScalarResult();

        return $result > 0;
    }

    /**
     * Obtener películas más recomendadas
     */
    public function findMostRecommendedMovies(int $limit = 10): array
    {
        return $this->createQueryBuilder('r')
            ->select('m.id, m.titulo, COUNT(r.id) as total')
            ->join('r.movie', 'm')
            ->groupBy('m.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener recomendaciones de una película
     */
    public function findByMovie(Movie $movie): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.movie = :movie')
            ->setParameter('movie', $movie)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener recomendaciones recientes
     */
    public function findRecent(int $limit = 10): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener usuarios que más recomiendan
     */
    public function findTopRecommenders(int $limit = 10): array
    {
        return $this->createQueryBuilder('r')
            ->select('u.id, u.nombre, u.apellido, COUNT(r.id) as total')
            ->join('r.fromUser', 'u')
            ->groupBy('u.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}