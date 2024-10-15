<?php

namespace App\Controller;

use App\Entity\Projet; // Assurez-vous d'importer l'entité correcte
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        // Récupérer tous les projets
        $projets = $this->entityManager->getRepository(Projet::class)->findAll();

        // Passer les projets à la vue
        return $this->render('dashboard/index.html.twig', [
            'projets' => $projets,
        ]);
    }


    // #[Route('/dashboard', name: 'app_dashboard')]
    // public function index(): Response
    // {
    //     return $this->render('dashboard/index.html.twig');
    // }

    
}
