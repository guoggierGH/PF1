<?php

namespace App\Repository;

use App\Entity\Review;
use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * Verificar si un usuario ya ha reseñado una película
     */
    public function hasUserReviewedMovie(User $user, Movie $movie): bool
    {
        $result = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.user = :user')
            ->andWhere('r.movie = :movie')
            ->setParameter('user', $user)
            ->setParameter('movie', $movie)
            ->getQuery()
            ->getSingleScalarResult();

        return $result > 0;
    }

    /**
     * Obtener reseña de un usuario para una película
     */
    public function findUserReviewForMovie(User $user, Movie $movie): ?Review
    {
        return $this->createQueryBuilder('r')
            ->where('r.user = :user')
            ->andWhere('r.movie = :movie')
            ->setParameter('user', $user)
            ->setParameter('movie', $movie)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Obtener reseñas recientes
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
     * Obtener reseñas de un usuario
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.user = :user')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener reseñas de una película
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
     * Calcular puntuación promedio de una película
     */
    public function getAverageRatingForMovie(Movie $movie): ?float
    {
        $result = $this->createQueryBuilder('r')
            ->select('AVG(r.puntuacion)')
            ->where('r.movie = :movie')
            ->setParameter('movie', $movie)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? round((float)$result, 1) : null;
    }

    /**
     * Contar reseñas de una película
     */
    public function countByMovie(Movie $movie): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.movie = :movie')
            ->setParameter('movie', $movie)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Obtener reseñas con comentarios (no vacíos)
     */
    public function findRecentWithComments(int $limit = 10): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.comentario IS NOT NULL')
            ->andWhere('r.comentario != :empty')
            ->setParameter('empty', '')
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}