<?php

namespace App\Controller\Admin;

use App\Entity\Candidature;
use App\Entity\Formation;
use App\Entity\User;
use App\Form\EditFormationType;
use App\Form\FormationType;
use App\Form\StatusGestionType;
use App\Repository\FormationRepository;
use App\Services\UploaderService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/formation", name="admin_formation_")
 */
class FormationController extends AbstractController
{
    /**
     * @Route("/liste", name="liste")
     */
    public function index(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findAll();
        
        return $this->render('admin/formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }

    /**
     * @Route("/liste/{id}", name="detail")
     */
    public function deatil(Formation $formation): Response
    {
        return $this->render('admin/formation/detail.html.twig', [
            'id' => $formation->getId(),
            'titre' => $formation->getTitre(),
            'description' => $formation->getDescription(),
            'nombrePlace' => $formation->getNombrePlace(),
            'datePublication' => $formation->getDatePublication(),
            'dateDebutFormation' => $formation->getDateDebutFormation(),
            'dateFinFormation' => $formation->getDateFinFormation(),
            'nomCategorie' => $formation->getCategorie()->getNom(),
            ''
        ]);
    }

    /**
     * @Route("/creation", name="creation")
     */
    public function creation(Request $request, EntityManagerInterface $em): Response
    {
        $formation = new Formation();

        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get("photoFile")->getData();

            $nom = uniqid();

            $imageNom = $nom . "." . $image->guessExtension();

            $image->move($this->getParameter("images_directory"), $imageNom);

            $formation->setImage($imageNom);

            $em->persist($formation);
            $em->flush();

            $this->addFlash(
                'message',
                "la formation" . $formation->getTitre() . "a ete creer avec success"
            );

            return $this->redirectToRoute('admin_formation_liste');
        }

        return $this->render('admin/formation/creation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edition/{id}", name="edition")
     */
    public function edition(Request $request, Formation $formation, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(EditFormationType::class, $formation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get("photoFile")->getData();

            $nom = uniqid();

            $imageNom = $nom . "." . $image->guessExtension();

            $image->move($this->getParameter("images_directory"), $imageNom);

            $formation->setImage($imageNom);

            $em->persist($formation);
            $em->flush();

            $this->addFlash(
                'message',
                "la formation" . $formation->getTitre() . "a ete modifier avec success"
            );

            return $this->redirectToRoute('admin_formation_liste');
        }

        return $this->render('admin/formation/edition.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="suppression")
     */
    public function suppresion(Request $request, Formation $formation, EntityManagerInterface $em): Response
    {
        $em->remove($formation);
        $em->flush();

        $this->addFlash(
            'message',
            "la formation" . $formation->getTitre() . "a ete supprimer avec success"
        );

        return $this->redirectToRoute('admin_formation_liste');
    }

    /**
     * @Route("/candidature/{id}", name="candidature")
     */
    public function candidature(Formation $formation): Response
    {
        // dd($formation);
        $titre = $formation->getTitre();
        $candidats = $formation->getCandidatures();

        return $this->render("admin/formation/candidatures.html.twig", [
            'titre' => $titre,
            'candidats' => $candidats
        ]);
    }

    /**
     * @Route("/candidature/suppression/{id}", name="candidature_suppression")
     */
    public function candidatureFichier(FormationRepository $formationRepository, $id): Response
    {
        $formation = $formationRepository->find($id);

        dd($formation);
        $fichiers = $formation->getCandidatures();
        return $this->render("admin/formation/fichiersDetail.html.twig", [
            // 'fichiers' => $fichiers
        ]);
    }

    /**
     * @Route("/candidature/formation/active/{id}", name="active_formation")
     */
    public function activationFormation(FormationRepository $formationRepository, $id, EntityManagerInterface $em): Response
    {
        $formation = $formationRepository->find($id);

        $formation->setIsActif(true);
        $em->flush();
        $this->addFlash(
            'message',
            "Vous avez activer la formation. Celle-ci s'affichera dans la liste des formations disponible"
        );

        return $this->redirectToRoute('admin_formation_liste');

    }


    /**
     * @Route("/candidature/formation/desactive/{id}", name="desactive_formation")
     */
    public function desactivationFormation(FormationRepository $formationRepository, $id, EntityManagerInterface $em): Response
    {
        $formation = $formationRepository->find($id);

            $formation->setIsActif(false);
            $em->flush();
            $this->addFlash(
                'message',
                "Vous avez descativer la formation. Celle-ci n'apparaitera pas dans la liste des formations disponible"
            );

        return $this->redirectToRoute('admin_formation_liste');

    }


    /**
     * @Route("/status/{id}", name="gestion_status")
     */
    public function gestionStatus(Candidature $candidature, EntityManagerInterface $em ,Request $request): Response
    {

        $form = $this->createForm(StatusGestionType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $status = $request->get('status_gestion')['status'];
            $candidature->setStatus($status);

            $em->flush();

            $this->addFlash(
                'success',
                "la candidature" . $candidature->getId() . " a ete accepté"
            );
            return $this->redirectToRoute('admin_formation_liste');
        }

        return $this->render('admin/formation/status.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
