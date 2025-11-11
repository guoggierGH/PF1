<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use App\Repository\GroupRepository;
use App\Repository\RecommendationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        MovieRepository $movieRepository,
        GroupRepository $groupRepository,
        RecommendationRepository $recommendationRepository
    ): Response
    {
        // Obtener películas recientes
        $recentMovies = $movieRepository->findRecent(8);
        
        // Obtener grupos populares
        $groups = $groupRepository->findMostPopular(6);
        
        // Si el usuario está autenticado, obtener sus recomendaciones
        $recommendations = [];
        if ($this->getUser()) {
            $recommendations = $recommendationRepository->findReceivedByUser(
                $this->getUser(), 
                true // solo no leídas
            );
        }

        return $this->render('home/index.html.twig', [
            'recentMovies' => $recentMovies,
            'groups' => $groups,
            'recommendations' => $recommendations,
        ]);
    }
}