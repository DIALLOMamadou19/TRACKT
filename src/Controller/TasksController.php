<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Tache;
use App\Form\TaskEditFormType;
use App\Form\TaskFormType;
use App\Repository\ProjetRepository;
use App\Repository\TacheRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TasksController extends AbstractController
{
    #[Route('/tasks/{projetId}', name: 'app_tasks', defaults: ['projetId' => null])]
    public function index(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, ProjetRepository $projetRepository, ?int $projetId = null): Response
    {
        $projets = $projetRepository->findAll();

        if ($projetId === null) {
            if (!empty($projets)) {
                $projet = $projets[0];
                // Redirect to the URL with the first project's ID
                return $this->redirectToRoute('app_tasks', ['projetId' => $projet->getId()]);
            } else {
                // Handle the case where there are no projects
                throw $this->createNotFoundException('Aucun projet trouvé');
            }
        } else {
            $projet = $projetRepository->find($projetId);
            if (!$projet) {
                throw $this->createNotFoundException('Projet non trouvé');
            }
        }

        $tasks = $entityManager->getRepository(Tache::class)->findBy(['projet' => $projet]);

        $groupedTasks = [
            'To do' => [],
            'In progress' => [],
            'Done' => [],
        ];

        foreach ($tasks as $task) {
            $groupedTasks[$task->getStatus()][] = [
                'id' => $task->getId(),
                'nomTache' => $task->getNomTache(),
                'descriptionTache' => $task->getDescriptionTache(),
                'DateDebut' => $task->getDateDebut(),
                'DateEcheance' => $task->getDateEcheance(),
                'status' => $task->getStatus(),
                'users' => $task->getUser()->map(function($user) {
                    return [
                        'id' => $user->getId(),
                        'username' => $user->getUsername()
                    ];
                })->toArray()
            ];
        }

        $form = $this->createForm(TaskFormType::class, new Tache());

        return $this->render('tasks/index.html.twig', [
            'groupedTasks' => $groupedTasks,
            'taskForm' => $form->createView(),
            'users' => $userRepository->findAll(),
            'projets' => $projets,
            'currentProjet' => $projet,
        ]);
    }

    #[Route('/tasks/{projetId}/new', name: 'app_task_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $projetId): JsonResponse
    {
        $task = new Tache();
        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setStatus('To do');
            $task->setCreatedAt(new \DateTime());

            // Ajoute uniquement les utilisateurs assignés à partir du formulaire
            $assignedUsers = $form->get('user')->getData();
            foreach ($assignedUsers as $user) {
                $task->addUser($user);
            }

            // Récupère le projet avec l'ID passé dans l'URL
            $projet = $entityManager->getRepository(Projet::class)->find($projetId);
            if (!$projet) {
                return new JsonResponse(['error' => "Projet avec l'ID $projetId non trouvé"], Response::HTTP_BAD_REQUEST);
            }
            $task->setProjet($projet);

            try {
                $entityManager->persist($task);
                $entityManager->flush();
                return new JsonResponse(['message' => 'Tâche créée avec succès', 'id' => $task->getId()], Response::HTTP_CREATED);
            } catch (\Exception $e) {
                // Log l'erreur
                $this->logger->error('Erreur lors de la création de la tâche : ' . $e->getMessage());
                return new JsonResponse(['error' => 'Une erreur est survenue lors de la création de la tâche'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return new JsonResponse(['errors' => $form->getErrors(true)->__toString()], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/tasks/delete/{id}', name: 'delete_task', methods: ['DELETE'])]
    public function delete(?Tache $task, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $entityManager->remove($task);
            $entityManager->flush();
            return new JsonResponse(['message' => 'Task deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while deleting the task'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/tasks/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tache $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskEditFormType::class, $task);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
                return $this->json(['message' => 'Task updated successfully']);
            }
            return $this->json(['errors' => $form->getErrors(true)->__toString()], 400);
        }

        return $this->render('tasks/_edit_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}   