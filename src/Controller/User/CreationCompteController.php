<?php

namespace App\Controller\User;

use App\Entity\Candidature;
use App\Entity\User;
use App\Form\CreationCompteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreationCompteController extends AbstractController
{
    /**
     * @Route("/creation-compte", name="utilisateur_creation_compte")
     */
    public function creationCompte(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(CreationCompteType::class, $user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            // dd($request);
            $password = $form->get("password")->getData();

            $passwordhash = $passwordHasher->hashPassword($user, $password);

            $user->setPassword($passwordhash);

            $em->persist($user);
            $em->flush();

            $this->addFlash(
               'message',
               'Felicitation votre compte a bien ete creer.Verifier votre boite mail pour activer votre compte'
            );

            return $this->redirectToRoute('utilisateur');
        }

        return $this->render("main/creationCompte.html.twig", [
            "form"=>$form->createView()
        ]);
    }

}
