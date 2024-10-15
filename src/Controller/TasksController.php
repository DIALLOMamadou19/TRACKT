<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Tache;
use App\Form\TaskFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TasksController extends AbstractController
{
   
    #[Route('/tasks', name: 'app_tasks')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tasks = $entityManager->getRepository(Tache::class)->findAll();
        $projets = $entityManager->getRepository(Projet::class)->findAll();
        $groupedTasks = [
            'To do' => [],
            'In progress' => [],
            'Done' => [],
        ];

        foreach ($tasks as $task) {
            $status = $task->getStatus();
            if (array_key_exists($status, $groupedTasks)) {
                $groupedTasks[$status][] = $task;
            } else {
                $groupedTasks['To do'][] = $task;
            }
        }

        $form = $this->createForm(TaskFormType::class, new Tache());
       

        return $this->render('tasks/index.html.twig', [
            'groupedTasks' => $groupedTasks,
            'taskForm' => $form->createView(),
            'projets' => $projets,
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

            // Get the current user
            $user = $this->getUser();
            if (!$user) {
                return new JsonResponse(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
            }
            $task->addUser($user);

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
}   