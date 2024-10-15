<?php 

namespace App\Controller;

use App\Entity\Tache;
use App\Entity\Projet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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
        // Récupérer les projets
        $projets = $this->entityManager->getRepository(Projet::class)->findAll();
        assert($projets !== null && count($projets) > 0, 'Aucun projet trouvé.');


        // Récupérer l'utilisateur connecté


        // Récupérer les tâches de l'utilisateur connecté
        $tasks = $this->entityManager->getRepository(Tache::class)->findAll();
        assert($tasks !== null && count($tasks) > 0, 'Aucune tâche trouvée.');

        // Récupérer le nombre total de tâches
        $totalTasks = count($tasks);

        // Passer les données à la vue
        return $this->render('dashboard/index.html.twig', [
            'projets' => $projets,
            'tasks' => $tasks,
            'totalTasks' => $totalTasks,
        ]);
    }
}
