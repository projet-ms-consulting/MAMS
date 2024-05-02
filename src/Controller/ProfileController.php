<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
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

    #[Route('/profile/edit', name: 'app_profile_edit')]
    #[IsGranted('ROLE_USER')]
    public function editProfile(UserInterface $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les informations du profil de l'utilisateur
        $firstName = $user->getfirstName();
        $lastName = $user->getlastName();
        $email = $user->getemail();

        // Créer le formulaire de modification du profil
        $form = $this->createForm(ProfileType::class, $user);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour les informations du profil de l'utilisateur
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page de profil
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);


    }
}