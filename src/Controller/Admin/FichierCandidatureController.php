<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Entity\User;
use App\Form\EditFormationType;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Services\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/formations", name="admin_formations_")
 */
class FichierCandidatureController extends AbstractController
{
    /**
     * @Route("/liste", name="liste")
     */
    public function index(FormationRepository $formationRepository): Response
    {

        return $this->render('admin/formation/indexFichier.html.twig', [
            'formations' => $formationRepository->findAll()
        ]);
    }

    /**
     * @Route("/candidatures/{id}", name="candidatures")
     */
    public function candidature(Formation $formation): Response
    {
        $titre = $formation->getTitre();
        $candidats = $formation->getCandidatures();

        return $this->render("admin/formation/fichiersCandidatures.html.twig", [
            'titre' => $titre,
            'candidats' => $candidats
        ]);
    }


}
