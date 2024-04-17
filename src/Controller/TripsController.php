<?php

namespace App\Controller;

use App\Entity\Trips;
use App\Form\TripsType;
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
        return $this->render('trips/index.html.twig', [
            'trips' => $tripsRepository->findAll(),
            ($this->getUser())
        ]);
    }

    #[Route('/new', name: 'app_trips_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trip = new Trips();
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
    public function show(Trips $trip): Response
    {
        return $this->render('trips/show.html.twig', [
            'trip' => $trip,
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

    #[Route('/{id}', name: 'app_trips_delete', methods: ['POST'])]
    public function delete(Request $request, Trips $trip, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trip->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trip);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trips_index', [], Response::HTTP_SEE_OTHER);
    }
}
