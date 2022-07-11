<?php

namespace App\Controller\User;

use App\Entity\Candidature;
use App\Entity\PieceJointe;
use App\Form\PostuleFormationType;
use App\Repository\FormationRepository;
use App\Services\UploaderFichiers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormationController extends AbstractController
{
    /**
     * @Route("/utilisateur/formations/postuler/{id}", name="user_postule_foramtion")
     */
    public function postul(Request $request, EntityManagerInterface $em, FormationRepository $formationRepository, $id, UploaderFichiers $uploader): Response
    {
        $candidature = new Candidature();
        $formation = $formationRepository->find($id);
        $user = $this->getUser();

        $form = $this->createForm(PostuleFormationType::class, $candidature);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cv = $request->files->get("postule_formation")["cv"];
            $diplome = $request->files->get("postule_formation")["diplome"];
            $lettreMotivation = $request->files->get("postule_formation")["lettre_motivation"];
            $photo = $request->files->get("postule_formation")["photo"];

            $nomNouveauCv = $uploader->upload($cv);
            $nomNouveauDiplome = $uploader->upload($diplome);
            $nomNouveauLettreMotivation = $uploader->upload($lettreMotivation);
            $nomNouveauPhoto = $uploader->upload($photo);

            $candidature->setUser($user);
            $candidature->setFormation($formation);
            $candidature->setCv($nomNouveauCv);
            $candidature->setDiplome($nomNouveauDiplome);
            $candidature->setLettreMotivation($nomNouveauLettreMotivation);
            $candidature->setPhoto($nomNouveauPhoto);

            $em->persist($candidature);
            $em->flush();

            $this->addFlash(
                'success',
                'Vous avez posuler avec succes a cette formation. Nous vous concterons apres selection de dossier'
            );

            return $this->redirectToRoute('utilisateur_candidature_liste');
        }

        return $this->render('user/candidature.html.twig', [
            'form' => $form->createView(),
            // 'formation' => $formation
        ]);
    }

    /**
     * @Route("/utilisateur/formations/suivis{id}", name="user_suivis_foramtion")
     */
    public function suivre(EntityManagerInterface $em, FormationRepository $formationRepository, $id): Response
    {
        $formation = $formationRepository->find($id);

        $formation->setSuivis(true);

        $em->flush();

        $this->addFlash(
            'message',
            'La formation ' . $formation->getTitre() . 'a ete ajouter dans la liste des formation a suivire'
        );

        return $this->redirectToRoute('formations');
    }

    /**
     * @Route("/utilisateur/formations/enlever{id}", name="user_enlever_foramtion")
     */
    public function passuivre(EntityManagerInterface $em, FormationRepository $formationRepository, $id): Response
    {
        $formation = $formationRepository->find($id);

        $formation->setSuivis(false);

        $this->addFlash(
            'messaage',
            'La formation ' . $formation->getTitre() . 'a ete supprimer de la liste des formation a suivire'
        );

        $em->flush();

        return $this->redirectToRoute('user_favoris_foramtion');
    }

    /**
     * @Route("/utilisateur/formations/favoris", name="user_favoris_foramtion")
     */
    public function favoris(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findBy(["suivis" => true], ["id" => "DESC"]);

        return $this->render("user/formation/favoris.html.twig", [
            "formations" => $formations
        ]);
    }

    /**
     * @Route("/utilisateur/formations/postuler/{id}", name="user_postul_foramtion")
     */
    public function postule(Request $request, EntityManagerInterface $em, FormationRepository $formationRepository, $id): Response
    {
        $candidature = new Candidature();
        // $fichiers = new PieceJointe();
        $formation = $formationRepository->find($id);
        // dd($formation);

        $form = $this->createForm(PostuleFormationType::class, $candidature);

        $user = $this->getUser();
        $candidature->setUser($user);
        $candidature->setFormation($formation);
        $form->handleRequest($request);
        $nom = md5(uniqid());

        if ($form->isSubmitted() && $form->isValid()) {
            $nomFichiers =  $request->files->get("postule_formation")["fichiers"];

            foreach ($nomFichiers as $nomFichier) {
                $nouveauNom = $nom . "." . $nomFichier->guessExtension();

                $nomFichier->move($this->getParameter("images_directory"), $nouveauNom);
            }

            // $fichiers->setFichiers($nouveauNom);
            // $fichiers->setCandidature($candidature);

            // $em->persist($fichiers);
            $em->persist($candidature);
            // dd($candidature);
            $em->flush();

            $this->addFlash(
                'success',
                'Vous avez posuler avec succes a cette formation. Nous vous concterons apres selection de dossier'
            );

            return $this->redirectToRoute('formations');
        }

        return $this->render('user/formation/candidature.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
