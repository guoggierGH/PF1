<?php

namespace App\Controller;

use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
    #[Route('/profile', name: 'user_profile', methods: ['GET'])]
    public function profile(): Response
    {
        return $this->render('user/profile.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/profile/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Perfil actualizado exitosamente.');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reviews', name: 'user_reviews', methods: ['GET'])]
    public function reviews(): Response
    {
        return $this->render('user/reviews.html.twig', [
            'reviews' => $this->getUser()->getReviews(),
        ]);
    }

    #[Route('/viewed-movies', name: 'user_viewed_movies', methods: ['GET'])]
    public function viewedMovies(): Response
    {
        return $this->render('user/viewed_movies.html.twig', [
            'movies' => $this->getUser()->getViewedMovies(),
        ]);
    }
}