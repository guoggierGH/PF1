<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\GroupRecommendation;
use App\Form\GroupType;
use App\Form\GroupRecommendationType;
use App\Repository\GroupRepository;
use App\Repository\GroupRecommendationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/group')]
#[IsGranted('ROLE_USER')]
class GroupController extends AbstractController
{
    #[Route('/', name: 'group_index', methods: ['GET'])]
    public function index(Request $request, GroupRepository $groupRepository): Response
    {
        $search = $request->query->get('search', '');
        
        // Grupos del usuario
        $myGroups = $groupRepository->findByUser($this->getUser());
        
        // Grupos disponibles (a los que no pertenece)
        if ($search) {
            $allGroups = $groupRepository->search($search);
            // Filtrar los que no son del usuario
            $availableGroups = array_filter($allGroups, function($group) use ($myGroups) {
                return !in_array($group, $myGroups);
            });
        } else {
            $availableGroups = $groupRepository->findAvailableForUser($this->getUser());
        }

        return $this->render('group/index.html.twig', [
            'myGroups' => $myGroups,
            'availableGroups' => $availableGroups,
        ]);
    }

    #[Route('/new', name: 'group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Agregar al creador como miembro
            $group->addMember($this->getUser());
            
            $entityManager->persist($group);
            $entityManager->flush();

            $this->addFlash('success', 'Grupo creado exitosamente.');

            return $this->redirectToRoute('group_show', ['id' => $group->getId()]);
        }

        return $this->render('group/new.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'group_show', methods: ['GET', 'POST'])]
    public function show(
        Group $group,
        Request $request,
        GroupRecommendationRepository $recommendationRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        // Verificar si el usuario es miembro
        $isMember = $group->getMembers()->contains($this->getUser());
        
        // Obtener recomendaciones del grupo
        $recommendations = $recommendationRepository->findByGroup($group);

        // Formulario de recomendación (solo para miembros)
        $recommendForm = null;
        if ($isMember) {
            $recommendation = new GroupRecommendation();
            $recommendForm = $this->createForm(GroupRecommendationType::class, $recommendation);
            $recommendForm->handleRequest($request);

            if ($recommendForm->isSubmitted() && $recommendForm->isValid()) {
                $recommendation->setUser($this->getUser());
                $recommendation->setGroup($group);
                
                $entityManager->persist($recommendation);
                $entityManager->flush();

                $this->addFlash('success', 'Recomendación enviada al grupo.');

                return $this->redirectToRoute('group_show', ['id' => $group->getId()]);
            }
        }

        return $this->render('group/show.html.twig', [
            'group' => $group,
            'isMember' => $isMember,
            'recommendations' => $recommendations,
            'recommendForm' => $recommendForm?->createView(),
        ]);
    }

    #[Route('/{id}/join', name: 'group_join', methods: ['POST'])]
    public function join(
        Group $group,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        if ($this->isCsrfTokenValid('join'.$group->getId(), $request->request->get('_token'))) {
            if (!$group->getMembers()->contains($this->getUser())) {
                $group->addMember($this->getUser());
                $entityManager->flush();

                $this->addFlash('success', 'Te has unido al grupo exitosamente.');
            }
        }

        return $this->redirectToRoute('group_show', ['id' => $group->getId()]);
    }

    #[Route('/{id}/leave', name: 'group_leave', methods: ['POST'])]
    public function leave(
        Group $group,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        if ($this->isCsrfTokenValid('leave'.$group->getId(), $request->request->get('_token'))) {
            if ($group->getMembers()->contains($this->getUser())) {
                $group->removeMember($this->getUser());
                $entityManager->flush();

                $this->addFlash('success', 'Has salido del grupo.');
            }
        }

        return $this->redirectToRoute('group_index');
    }

    #[Route('/{id}/edit', name: 'group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Group $group, EntityManagerInterface $entityManager): Response
    {
        // Solo miembros pueden editar
        if (!$group->getMembers()->contains($this->getUser())) {
            $this->addFlash('error', 'No tienes permiso para editar este grupo.');
            return $this->redirectToRoute('group_index');
        }

        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Grupo actualizado exitosamente.');

            return $this->redirectToRoute('group_show', ['id' => $group->getId()]);
        }

        return $this->render('group/edit.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'group_delete', methods: ['POST'])]
    public function delete(Request $request, Group $group, EntityManagerInterface $entityManager): Response
    {
        // Solo miembros pueden eliminar
        if (!$group->getMembers()->contains($this->getUser())) {
            $this->addFlash('error', 'No tienes permiso para eliminar este grupo.');
            return $this->redirectToRoute('group_index');
        }

        if ($this->isCsrfTokenValid('delete'.$group->getId(), $request->request->get('_token'))) {
            $entityManager->remove($group);
            $entityManager->flush();

            $this->addFlash('success', 'Grupo eliminado exitosamente.');
        }

        return $this->redirectToRoute('group_index');
    }
}