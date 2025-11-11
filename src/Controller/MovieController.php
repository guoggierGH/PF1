<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\MovieType;
use App\Form\ReviewType;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('/', name: 'movie_index', methods: ['GET'])]
    public function index(Request $request, MovieRepository $movieRepository): Response
    {
        // Obtener parámetros de búsqueda y filtros
        $search = $request->query->get('search', '');
        $genre = $request->query->get('genre', '');
        $year = $request->query->get('year', '');

        // Buscar películas con filtros
        if ($search) {
            $movies = $movieRepository->search($search);
        } elseif ($genre) {
            $movies = $movieRepository->findByGenero($genre);
        } elseif ($year) {
            $movies = $movieRepository->findByYear((int)$year);
        } else {
            $movies = $movieRepository->findBy([], ['createdAt' => 'DESC']);
        }

        // Obtener todos los géneros para el filtro
        $genres = $movieRepository->findAllGenres();

        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
            'genres' => $genres,
        ]);
    }

    #[Route('/search', name: 'movie_search', methods: ['GET'])]
    public function search(Request $request, MovieRepository $movieRepository): Response
    {
        $query = $request->query->get('q', '');
        $movies = $query ? $movieRepository->search($query) : [];

        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
            'genres' => $movieRepository->findAllGenres(),
        ]);
    }

    #[Route('/new', name: 'movie_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($movie);
            $entityManager->flush();

            $this->addFlash('success', 'Película creada exitosamente.');

            return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
        }

        return $this->render('movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'movie_show', methods: ['GET', 'POST'])]
    public function show(
        Movie $movie,
        Request $request,
        ReviewRepository $reviewRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        // Obtener reseñas de la película
        $reviews = $reviewRepository->findByMovie($movie);
        
        // Calcular promedio y conteo
        $averageRating = $reviewRepository->getAverageRatingForMovie($movie);
        $reviewCount = $reviewRepository->countByMovie($movie);

        // Verificar si el usuario ya vio la película
        $hasViewed = false;
        $userReview = null;
        
        if ($this->getUser()) {
            $hasViewed = $this->getUser()->hasViewedMovie($movie);
            $userReview = $reviewRepository->findUserReviewForMovie($this->getUser(), $movie);
        }

        // Formulario de reseña
        $review = $userReview ?? new Review();
        $reviewForm = null;
        
        if ($this->getUser()) {
            $reviewForm = $this->createForm(ReviewType::class, $review);
            $reviewForm->handleRequest($request);

            if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
                $review->setUser($this->getUser());
                $review->setMovie($movie);
                
                if (!$userReview) {
                    $entityManager->persist($review);
                } else {
                    $review->setUpdatedAt(new \DateTime());
                }
                
                $entityManager->flush();

                $this->addFlash('success', 'Reseña guardada exitosamente.');

                return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
            }
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'reviewCount' => $reviewCount,
            'hasViewed' => $hasViewed,
            'userReview' => $userReview,
            'reviewForm' => $reviewForm?->createView(),
        ]);
    }

    #[Route('/{id}/mark-viewed', name: 'movie_mark_viewed', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function markAsViewed(
        Movie $movie,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        if ($this->isCsrfTokenValid('mark_viewed'.$movie->getId(), $request->request->get('_token'))) {
            $this->getUser()->addViewedMovie($movie);
            $entityManager->flush();

            $this->addFlash('success', 'Película marcada como vista.');
        }

        return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
    }

    #[Route('/{id}/edit', name: 'movie_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Movie $movie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie->setUpdatedAt(new \DateTime());
            $entityManager->flush();

            $this->addFlash('success', 'Película actualizada exitosamente.');

            return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
        }

        return $this->render('movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'movie_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Movie $movie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($movie);
            $entityManager->flush();

            $this->addFlash('success', 'Película eliminada exitosamente.');
        }

        return $this->redirectToRoute('movie_index');
    }
}