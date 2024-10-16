<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    
    #[Route('/profile', name: 'profile')]
    public function index(Request $request): Response
    {
        $user = $this->getUser(); // Assuming you are using security

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/profile/edit", name="profile_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();

        // Handle form submission for editing profile

        return $this->render('profile/edit.html.twig', [
            'user' => $user,
        ]);
    }

}
