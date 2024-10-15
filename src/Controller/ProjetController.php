<?php
// src/Controller/ProjectController.php

namespace App\Controller;

use App\Entity\Projet; // Assurez-vous d'importer l'entité correcte
use Doctrine\ORM\EntityManagerInterface; // Importer l'interface de l'EntityManager
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProjetController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/projets', name: 'app_projet')]
    public function index(): Response
    {
        // Récupérer les projets depuis la base de données
        $projets = $this->entityManager->getRepository(Projet::class)->findAll();

        // Passer les projets à la vue
        return $this->render('projet/index.html.twig', [
            'projets' => $projets,
        ]);
    }
}
