<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use App\Repository\ReviewRepository;
use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function dashboard(
        MovieRepository $movieRepository,
        UserRepository $userRepository,
        ReviewRepository $reviewRepository,
        GroupRepository $groupRepository
    ): Response
    {
        // Estadísticas generales
        $stats = [
            'totalMovies' => count($movieRepository->findAll()),
            'totalUsers' => count($userRepository->findAll()),
            'totalReviews' => count($reviewRepository->findAll()),
            'totalGroups' => count($groupRepository->findAll()),
        ];

        // Películas recientes
        $recentMovies = $movieRepository->findRecent(5);
        
        // Películas mejor valoradas
        $topRatedMovies = $movieRepository->findTopRated(5);
        
        // Distribución por género
        $genreDistribution = $movieRepository->countByGenre();

        return $this->render('admin/dashboard.html.twig', [
            'stats' => $stats,
            'recentMovies' => $recentMovies,
            'topRatedMovies' => $topRatedMovies,
            'genreDistribution' => $genreDistribution,
        ]);
    }

    #[Route('/users', name: 'admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/reports', name: 'admin_reports')]
    public function reports(
        MovieRepository $movieRepository,
        UserRepository $userRepository,
        ReviewRepository $reviewRepository
    ): Response
    {
        // Usuarios más activos
        $topReviewers = $userRepository->getUsersWithMostReviews(10);
        
        // Películas más populares
        $popularMovies = $movieRepository->findTopRated(10);

        return $this->render('admin/reports.html.twig', [
            'topReviewers' => $topReviewers,
            'popularMovies' => $popularMovies,
        ]);
    }
}