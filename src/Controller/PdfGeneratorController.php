<?php

namespace App\Controller;

use App\Entity\Trips;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;

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

        // Utiliser Dompdf pour générer le PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Renvoyer le fichier PDF en réponse HTTP
        return new Response(
            $dompdf->stream('trips.pdf'), // Nom du fichier PDF à afficher
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
}
