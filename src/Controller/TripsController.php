<?php

namespace App\Controller;

use App\Entity\Trips;
use App\Form\TripsType;
use App\Repository\ExpensesRepository;
use App\Repository\TripsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/trips')]
class TripsController extends AbstractController
{
    #[Route('/', name: 'app_trips_index', methods: ['GET'])]
    public function index(TripsRepository $tripsRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer les voyages associés à l'utilisateur connecté
        $trips = $tripsRepository->findBy(['user' => $user]);

        return $this->render('trips/index.html.twig', [
            'trips' => $trips,
        ]);
    }

    #[Route('/new', name: 'app_trips_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trip = new Trips();

        // Associer le voyage à l'utilisateur connecté
        $user = $this->getUser();
        $trip->setUser($user);

        $form = $this->createForm(TripsType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trip);
            $entityManager->flush();

            return $this->redirectToRoute('app_trips_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trips/new.html.twig', [
            'trip' => $trip,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trips_show', methods: ['GET'])]
    public function show(Trips $trip, EntityManagerInterface $entityManager,ExpensesRepository $expensesRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer les voyages associés à l'utilisateur connecté
        $tripsRepository = $entityManager->getRepository(Trips::class);
        $trips = $tripsRepository->findBy(['user' => $user]);

        $expenses = $expensesRepository->findBy(['trips' => $trip]);

        return $this->render('trips/show.html.twig', [
            'trip' => $trip,
            'expenses' => $expenses,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trips_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trips $trip, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TripsType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_trips_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trips/edit.html.twig', [
            'trip' => $trip,
            'form' => $form,
        ]);
    }

    #[Route('/trips/{id}', name: 'app_trips_delete', methods: ['POST'])]
    public function delete(Request $request, Trips $trip, EntityManagerInterface $entityManager, ExpensesRepository $expensesRepository): Response
    {
        // Vérifiez si le jeton CSRF est valide
        if ($this->isCsrfTokenValid('delete'.$trip->getId(), $request->request->get('_token'))) {
            // Récupérez les dépenses associées à ce voyage
            $expenses = $expensesRepository->findBy(['trips' => $trip]);

            // Supprimez les dépenses associées à ce voyage
            foreach ($expenses as $expense) {
                $entityManager->remove($expense);
            }

            // Supprimez le voyage lui-même
            $entityManager->remove($trip);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trips_index');

}

}
