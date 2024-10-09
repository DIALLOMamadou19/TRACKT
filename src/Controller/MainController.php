<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class MainController
{
    #[Route('/old_homepage')]
    public function homepage()
    {
        return new Response('<strong>Starshop</>: Your monopoly is good');
    }
}