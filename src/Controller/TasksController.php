<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Tache;
use App\Form\TaskFormType;
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
    #[Route('/tasks', name: 'app_tasks')]
    public function index(EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $tasks = $entityManager->getRepository(Tache::class)->findAll();

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
        ]);
    }

    #[Route('/task/new', name: 'app_task_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $task = new Tache();
        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setStatus('To do');
            $task->setCreatedAt(new \DateTime());

            // Add only the assigned users from the form
            $assignedUsers = $form->get('user')->getData();
            foreach ($assignedUsers as $user) {
                $task->addUser($user);
            }

            // Set the projet with ID 1
            $projet = $entityManager->getRepository(Projet::class)->find(1);
            if (!$projet) {
                return new JsonResponse(['error' => 'Project with ID 1 not found'], Response::HTTP_BAD_REQUEST);
            }
            $task->setProjet($projet);

            try {
                $entityManager->persist($task);
                $entityManager->flush();
                return new JsonResponse(['message' => 'Task created successfully', 'id' => $task->getId()], Response::HTTP_CREATED);
            } catch (\Exception $e) {
                // Log the error
                $this->logger->error('Error creating task: ' . $e->getMessage());
                return new JsonResponse(['error' => 'An error occurred while creating the task'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return new JsonResponse(['errors' => $form->getErrors(true)->__toString()], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/tasks/{id}', name: 'delete_task', methods: ['DELETE'])]
    public function delete(Tache $task, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($task);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Task deleted successfully'], 200);
    }

    #[Route('/tasks/{id}/edit', name: 'app_task_edit', methods: ['POST'])]
    public function edit(Request $request, Tache $task, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $task->setNomTache($data['nom_tache']);
        $task->setDescriptionTache($data['description_tache']);
        $task->setDateDebut(new \DateTime($data['date_debut']));
        $task->setDateEcheance(new \DateTime($data['date_echeance']));
        $task->setStatus($data['status']);

        $entityManager->flush();

        return $this->json(['message' => 'Task updated successfully']);
    }
}