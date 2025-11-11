<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * Buscar películas por título, género o director
     */
    public function search(string $query): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.titulo LIKE :query')
            ->orWhere('m.genero LIKE :query')
            ->orWhere('m.director LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('m.titulo', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener películas más recientes
     */
    public function findRecent(int $limit = 10): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener películas mejor puntuadas
     */
    public function findTopRated(int $limit = 10): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.reviews', 'r')
            ->groupBy('m.id')
            ->having('COUNT(r.id) > 0')
            ->orderBy('AVG(r.puntuacion)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener películas por género
     */
    public function findByGenero(string $genero): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.genero = :genero')
            ->setParameter('genero', $genero)
            ->orderBy('m.titulo', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener películas por año
     */
    public function findByYear(int $year): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.anio = :year')
            ->setParameter('year', $year)
            ->orderBy('m.titulo', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener películas no vistas por un usuario
     */
    public function findNotViewedByUser($userId): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.viewedByUsers', 'u', 'WITH', 'u.id = :userId')
            ->where('u.id IS NULL')
            ->setParameter('userId', $userId)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtener todos los géneros disponibles
     */
    public function findAllGenres(): array
    {
        $result = $this->createQueryBuilder('m')
            ->select('DISTINCT m.genero')
            ->orderBy('m.genero', 'ASC')
            ->getQuery()
            ->getResult();

        return array_column($result, 'genero');
    }

    /**
     * Contar películas por género
     */
    public function countByGenre(): array
    {
        return $this->createQueryBuilder('m')
            ->select('m.genero, COUNT(m.id) as total')
            ->groupBy('m.genero')
            ->orderBy('total', 'DESC')
            ->getQuery()
            ->getResult();
    }
}