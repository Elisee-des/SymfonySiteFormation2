<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Form\EditCategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin", name="admin_")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(CategorieRepository $categorieRepo): Response
    {
        return $this->render('admin/categorie/index.html.twig', [
            'categories' => $categorieRepo->findAll(),
        ]);
    }

    /**
     * @Route("/categorie/creation", name="creation_categorie")
     */
    public function creation(Request $request, EntityManagerInterface $em): Response
    {
        $categorie  = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie);

        $nom = uniqid();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageNom = $request->files->get("categorie")["images"];

            $nouveauNom = $nom . "." . $imageNom->guessExtension();

            $imageNom->move($this->getParameter("images_directory"), $nouveauNom);

            $categorie->setImage($nouveauNom);

            $em->persist($categorie);
            $em->flush();

            $this->addFlash(
                'success',
                "La categorie de " . $categorie->getNom() . " a ete creer avec success"
            );

            return $this->redirectToRoute('admin_categorie');
        }
        return $this->render('admin/categorie/creation.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/categorie/edition/{id}", name="edition_categorie")
     */
    public function edition(Categorie $categorie, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EditCategorieType::class, $categorie);

        $form->handleRequest($request);

        $nom = uniqid();

        if ($form->isSubmitted() && $form->isValid()) {

            // dd($request);

            $imageNom = $request->files->get("edit_categorie")["images"];

            $nouveauNom = $nom . "." . $imageNom->guessExtension();

            $imageNom->move($this->getParameter("images_directory"), $nouveauNom);

            $categorie->setImage($nouveauNom);

            $em->persist($categorie);
            $em->flush();

            $this->addFlash(
                'success',
                "La categorie de" . $categorie->getNom() . " a ete modifier avec success"
            );
            return $this->redirectToRoute('admin_categorie');
        }
        return $this->render('admin/categorie/edition.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/categorie/suppression/{id}", name="suppresion_categorie")
     */
    public function suppression(Categorie $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();

        $this->addFlash(
            'success',
            "La categorie de " . $user->getNom() . " a ete supprimer avec success"
        );
        return $this->redirectToRoute('admin_categorie');
    }
}
