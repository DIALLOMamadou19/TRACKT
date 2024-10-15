<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Projet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectsController extends AbstractController
{
    #[Route('/projects', name: 'app_projects')]
    public function index(EntityManagerInterface $em): Response
    {
        // Récupérer tous les projets si nécessaire
        $projects = $em->getRepository(Projet::class)->findAll();

        // Pour l'instant, nous passons juste un tableau vide, mais vous pouvez passer $projects
        return $this->render('project/projects.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/projects/create', name: 'app_project_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        // Récupérer les données du formulaire envoyé via POST
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        //$assignedUsers = $request->request->get('assigned_users');

        // Création d'un nouveau projet
        $project = new Projet();
        $project->setNomProjet($name);
        $project->setDescription($description);
        //$project->setAssignedUsers($assignedUsers); // On suppose que c'est une chaîne ou un tableau

        // Sauvegarder le projet en base de données
        $em->persist($project);
        $em->flush();

        // Rediriger vers la liste des projets après la création
        return $this->redirectToRoute('app_projects');
    }

    // ProjectsController.php

    #[Route('/projects/delete/{id}', name: 'app_project_delete', methods: ['POST', 'DELETE'])]
    public function delete(int $id, EntityManagerInterface $em, Request $request): Response
    {
        // Récupérer le projet par son ID
        $project = $em->getRepository(Projet::class)->find($id);

        // Vérifier si le projet existe
        if (!$project) {
            throw $this->createNotFoundException('Le projet n\'existe pas.');
        }

        // Si la méthode POST est utilisée (par exemple via un formulaire de suppression)
        if ($this->isCsrfTokenValid('delete_project_' . $project->getId(), $request->request->get('_token'))) {
            // Supprimer le projet
            $em->remove($project);
            $em->flush();

            // Redirection après suppression
            return $this->redirectToRoute('app_projects');
        }

        return $this->redirectToRoute('app_projects');
    }


    #[Route('/projects/edit/{id}', name: 'app_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, int $id): Response
    {
        // Récupérer le projet par son ID
        $project = $em->getRepository(Projet::class)->find($id);

        // Vérifier si le projet existe
        if (!$project) {
            throw $this->createNotFoundException('Le projet n\'existe pas.');
        }

        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $name = $request->request->get('name');
            $description = $request->request->get('description');

            // Mettre à jour le projet
            $project->setNomProjet($name);
            $project->setDescription($description);

            // Enregistrer les modifications
            $em->flush();

            // Rediriger vers la liste des projets après modification
            return $this->redirectToRoute('app_projects');
        }

        // Afficher le formulaire de modification
        return $this->render('project/edit.html.twig', [
            'project' => $project,
        ]);
    }


}
