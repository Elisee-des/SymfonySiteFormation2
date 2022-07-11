<?php

namespace App\Controller\User;

use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidatureController extends AbstractController
{
    /**
     * @Route("/utilisateur/candidature/liste", name="utilisateur_candidature_liste")
     */
    public function candidature(): Response
    {
        /**
         * @var User
         */
        $user = $this->getUser();
        $candidatures = $user->getCandidatures();

        return $this->render("user/candidature/candidature.html.twig", [
            "candidatures" => $candidatures
        ]);
    }

    /**
     * @Route("/utilisateur/candidature/supprimer/{id}", name="utilisature_supprimer_foramtion")
     */
    public function supprimer(EntityManagerInterface $em, Candidature $candidature): Response
    {

        $em->remove($candidature);
        $em->flush();

        $this->addFlash(
            'success',
            'Vous avez retirer avec succes votre candidature'
        );

        return $this->redirectToRoute('utilisateur_candidature_liste');
    }
}
