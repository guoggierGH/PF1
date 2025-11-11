<?php

namespace App\Controller;

use App\Entity\Recommendation;
use App\Form\RecommendationType;
use App\Repository\RecommendationRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recommendation')]
#[IsGranted('ROLE_USER')]
class RecommendationController extends AbstractController
{
    #[Route('/', name: 'recommendation_index', methods: ['GET'])]
    public function index(RecommendationRepository $recommendationRepository): Response
    {
        // Obtener recomendaciones recibidas y enviadas
        $received = $recommendationRepository->findReceivedByUser($this->getUser());
        $sent = $recommendationRepository->findSentByUser($this->getUser());
        
        // Marcar como leídas las recibidas
        $recommendationRepository->markAsRead($this->getUser());

        return $this->render('recommendation/index.html.twig', [
            'received' => $received,
            'sent' => $sent,
        ]);
    }

    #[Route('/new', name: 'recommendation_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        MovieRepository $movieRepository
    ): Response
    {
        $recommendation = new Recommendation();
        
        // Si se pasa un movieId, pre-seleccionar la película
        $movieId = $request->query->get('movieId');
        if ($movieId) {
            $movie = $movieRepository->find($movieId);
            if ($movie) {
                $recommendation->setMovie($movie);
            }
        }
        
        $form = $this->createForm(RecommendationType::class, $recommendation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recommendation->setFromUser($this->getUser());
            
            $entityManager->persist($recommendation);
            $entityManager->flush();

            $this->addFlash('success', 'Recomendación enviada exitosamente.');

            return $this->redirectToRoute('recommendation_index');
        }

        return $this->render('recommendation/new.html.twig', [
            'recommendation' => $recommendation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'recommendation_show', methods: ['GET'])]
    public function show(Recommendation $recommendation): Response
    {
        // Verificar que el usuario sea el emisor o receptor
        if ($recommendation->getFromUser() !== $this->getUser() && 
            $recommendation->getToUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tienes acceso a esta recomendación.');
        }

        // Si es el receptor, marcar como visto
        if ($recommendation->getToUser() === $this->getUser() && !$recommendation->isVisto()) {
            $recommendation->setVisto(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        return $this->render('recommendation/show.html.twig', [
            'recommendation' => $recommendation,
        ]);
    }

    #[Route('/{id}', name: 'recommendation_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Recommendation $recommendation,
        EntityManagerInterface $entityManager
    ): Response
    {
        // Solo el emisor puede eliminar
        if ($recommendation->getFromUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No puedes eliminar esta recomendación.');
        }

        if ($this->isCsrfTokenValid('delete'.$recommendation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recommendation);
            $entityManager->flush();

            $this->addFlash('success', 'Recomendación eliminada exitosamente.');
        }

        return $this->redirectToRoute('recommendation_index');
    }
}