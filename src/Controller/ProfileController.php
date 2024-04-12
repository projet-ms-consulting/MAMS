<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function showProfile(UserInterface $user): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non connecté.');
        }

        // Récupérer les informations du profil de l'utilisateur
        $firstName = $user->getfirstName();
        $lastName = $user->getlastName();
        $email = $user->getemail();

        return $this->render('profile/show.html.twig', [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
        ]);
    }
}