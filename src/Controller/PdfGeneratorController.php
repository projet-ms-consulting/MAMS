<?php

namespace App\Controller;

use App\Entity\Trips;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PdfGeneratorController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/pdf/generator', name: 'app_pdf_generator')]
    public function generatePdf(): Response
    {
        // Récupérer les données des voyages depuis la base de données
        $trips = $this->entityManager->getRepository(Trips::class)->findAll();

        // Générer le contenu HTML en passant les données au template Twig
        $html = $this->renderView('pdf_generator/index.html.twig', [
            'trips' => $trips,
        ]);

        // Configuration de Dompdf pour définir les marges et autres options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        // Utiliser Dompdf pour générer le PDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Utiliser le format paysage


// Rendre le PDF
        $dompdf->render();

// Si nécessaire, ajouter une pagination
        $dompdf->getOptions()->setIsPhpEnabled(true); // Activer le support PHP pour les pages suivantes


// Renvoyer le fichier PDF en réponse HTTP
        return new Response(
            $dompdf->output(), // Contenu PDF
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
}