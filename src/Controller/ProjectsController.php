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
    #[Route('/projets', name: 'app_projets')]
    public function index(EntityManagerInterface $em): Response
    {
        // Récupérer tous les projets si nécessaire
        $projets = $em->getRepository(Projet::class)->findAll();

        // Pour l'instant, nous passons juste un tableau vide, mais vous pouvez passer $projets
        return $this->render('projet/projets.html.twig', [
            'projets' => $projets,
        ]);
    }

    #[Route('/projets/create', name: 'app_projet_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        // Récupérer les données du formulaire envoyé via POST
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        //$assignedUsers = $request->request->get('assigned_users');

        // Création d'un nouveau projet
        $projet = new Projet();
        $projet->setNomProjet($name);
        $projet->setDescription($description);
        //$projet->setAssignedUsers($assignedUsers); // On suppose que c'est une chaîne ou un tableau

        // Sauvegarder le projet en base de données
        $em->persist($projet);
        $em->flush();

        // Rediriger vers la liste des projets après la création
        return $this->redirectToRoute('app_projets');
    }

    // projetsController.php

    #[Route('/projets/delete/{id}', name: 'app_projet_delete', methods: ['POST', 'DELETE'])]
    public function delete(int $id, EntityManagerInterface $em, Request $request): Response
    {
        // Récupérer le projet par son ID
        $projet = $em->getRepository(Projet::class)->find($id);

        // Vérifier si le projet existe
        if (!$projet) {
            throw $this->createNotFoundException('Le projet n\'existe pas.');
        }

        // Si la méthode POST est utilisée (par exemple via un formulaire de suppression)
        if ($this->isCsrfTokenValid('delete_projet_' . $projet->getId(), $request->request->get('_token'))) {
            // Supprimer le projet
            $em->remove($projet);
            $em->flush();

            // Redirection après suppression
            return $this->redirectToRoute('app_projets');
        }

        return $this->redirectToRoute('app_projets');
    }


    #[Route('/projets/edit/{id}', name: 'app_projet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, int $id): Response
    {
        // Récupérer le projet par son ID
        $projet = $em->getRepository(Projet::class)->find($id);

        // Vérifier si le projet existe
        if (!$projet) {
            throw $this->createNotFoundException('Le projet n\'existe pas.');
        }

        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $name = $request->request->get('name');
            $description = $request->request->get('description');

            // Mettre à jour le projet
            $projet->setNomProjet($name);
            $projet->setDescription($description);

            // Enregistrer les modifications
            $em->flush();

            // Rediriger vers la liste des projets après modification
            return $this->redirectToRoute('app_projets');
        }

        // Afficher le formulaire de modification
        return $this->render('projet/edit.html.twig', [
            'projet' => $projet,
        ]);
    }


}
