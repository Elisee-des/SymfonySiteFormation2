<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/admin", name="admin_")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="utilisateur_liste")
     */
    public function index(UserRepository $userRepo, CacheInterface $cacheItemInterface): Response
    {
        $user = $cacheItemInterface->get('utilisateur_liste', function(ItemInterface $itemInterface) use ($userRepo){
            $itemInterface->expiresAfter(3000);
            return $userRepo->findAll();
        });

        return $this->render('admin/users/index.html.twig', [
            'users' => $userRepo->findAll(),
        ]);
    }

    /**
     * @Route("/users/creation", name="utilisateur_creation")
     */
    public function creation(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasherInterface): Response
    {
        $user  = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nom = uniqid();

            $nomImage = $form->get("photoFile")->getData();

            $ancienPassword = $form->get("password")->getData();

            $nouveauNom = $nom . "." . $nomImage->guessExtension();

            $nomImage->move($this->getParameter("images_directory"), $nouveauNom);

            $nouveauPassword = $passwordHasherInterface->hashPassword($user, $ancienPassword);

            $user->setPassword($nouveauPassword);

            $user->setPhoto($nouveauNom);

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                "utilisateur " . $user->getNom() . " " . $user->getPrenom() . " a ete creer avec success"
            );

            return $this->redirectToRoute('admin_utilisateur_liste');
        }
        return $this->render('admin/users/creation.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/users/edition/{id}", name="utilisateur_edition")
     */
    public function edition(User $user, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasherInterface): Response
    {
        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nom = uniqid();

            // $nomImage = $form->get("photoFile")->getData();

            // $ancienPassword = $form->get("password")->getData();

            // $nouveauNom = $nom.".".$nomImage->guessExtension();

            // $nomImage->move($this->getParameter("images_directory"), $nouveauNom);
            
            // $nouveauPassword = $passwordHasherInterface->hashPassword($user, $ancienPassword);

            // $user->setPhoto($nouveauNom);

            // $user->setPassword($nouveauPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                "utilisateur  " . $user->getNom() . " " . $user->getPrenom() . " a ete modifier avec success"
            );

            return $this->redirectToRoute('admin_utilisateur_liste');
        }
        return $this->render('admin/users/edition.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/users/suppression/{id}", name="utilisateur_suppression")
     */
    public function suppression(User $user, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($user);
        $em->flush();

        $this->addFlash(
            'success',
            "utilisateur " . $user->getNom() . " " . $user->getPrenom() . " a ete supprimer avec success"
        );
        return $this->redirectToRoute('admin_utilisateur_liste');
    }
}
