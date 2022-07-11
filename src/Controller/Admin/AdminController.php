<?php

namespace App\Controller\Admin;

use App\Form\CategorieType;
use App\Form\EditPasswordUserType;
use App\Form\EditPhotoUserType;
use App\Form\ModificationProfilType;
use App\Repository\CandidatureRepository;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(
        FormationRepository $formationRepository,
        CandidatureRepository $candidatureRepository,
        CategorieRepository $categorieRepository,
        UserRepository $userRepository,
        VilleRepository $villeRepository
    ): Response {
        $formations = $formationRepository->findAll();
        $candidatures = $candidatureRepository->findAll();
        $categories = $categorieRepository->findAll();
        $users = $userRepository->findAll();
        $villes = $villeRepository->findAll();

        $totalFormation = count($formations);
        $totalCategorie = count($categories);
        $totalVille = count($villes);
        $totalCandidature = count($candidatures);
        $totalUser = count($users);

        return $this->render('admin/index.html.twig', [
            'totalFormations' => $totalFormation,
            'totalCandidatures' => $totalCandidature,
            'totalCategories' => $totalCategorie,
            'totalVille' => $totalVille,
            'totalUser' => $totalUser
        ]);
    }

    /**
     * @Route("/parametre", name="parametre")
     */
    public function parametre(): Response
    {
        return $this->render('admin/parametre/index.html.twig');
    }


    /**
     * @Route("/utilisateur/parametre/edite-profil", name="edit_profil_parametre")
     */
    public function editProfil(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ModificationProfilType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'message',
                'Vous avez modifier avec succes votre profil'
            );

            return $this->redirectToRoute('admin_parametre');
        }

        return $this->render('admin/parametre/editProfil.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/utilisateur/parametre/edite-photo", name="edit_photo_parametre")
     */
    public function editImage(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(EditPhotoUserType::class, $user);

        $form->handleRequest($request);

        $nom = uniqid();

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $request->files->get("edit_photo_user")["photoProfil"];

            $nouveauNom = $nom . '.' . $photo->guessExtension();


            $photo->move($this->getParameter('images_directory'), $nouveauNom);
            // dd($photo);

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'message',
                'Vous avez modifier avec succes votre photo de profil'
            );

            return $this->redirectToRoute('admin_parametre');
        }

        return $this->render('admin/parametre/editPhoto.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/utilisateur/parametre/edite-password", name="edit_password_parametre")
     */
    public function editPassword(Request $request, UserPasswordHasherInterface $hashpassword, EntityManagerInterface $em): Response
    {
        /**
         * @var User
         */
        $user = $this->getUser();
        $form = $this->createForm(EditPasswordUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $request->get("edit_password_user")["password"]["first"];
            $passwordHash = $hashpassword->hashPassword($user, $password);
            $user->setPassword($passwordHash);

            $em->flush();

            $this->addFlash(
                'message',
                'Vous avez modifier avec succes votre mot de passe'
            );

            return $this->redirectToRoute('admin_parametre');
        }

        return $this->render('admin/parametre/editPassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
