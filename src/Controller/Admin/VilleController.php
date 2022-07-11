<?php

namespace App\Controller\Admin;

use App\Entity\Ville;
use App\Form\EditVilleType;
use App\Form\ExportationVilleType;
use App\Form\ImportationVilleType;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin", name="admin_")
 */
class VilleController extends AbstractController
{
    /**
     * @Route("/ville", name="ville")
     */
    public function index(VilleRepository $villeRepo): Response
    {
        return $this->render('admin/ville/index.html.twig', [
            'villes' => $villeRepo->findAll(),
        ]);
    }

    /**
     * @Route("/ville/creation", name="creation_ville")
     */
    public function creation(Request $request, EntityManagerInterface $em): Response
    {
        $ville  = new Ville();

        $form = $this->createForm(VilleType::class, $ville);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($ville);
            $em->flush();

            $this->addFlash(
                'success',
                "La ville de " . $ville->getNom() . " a ete creer avec success"
            );

            return $this->redirectToRoute('admin_ville');
        }
        return $this->render('admin/ville/creation.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/ville/edition/{id}", name="edition_ville")
     */
    public function edition(Ville $ville, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EditVilleType::class, $ville);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($ville);
            $em->flush();

            $this->addFlash(
                'success',
                "La ville de" . $ville->getNom() . " a ete modifier avec success"
            );
            return $this->redirectToRoute('admin_ville');
        }
        return $this->render('admin/ville/edition.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ville/suppression/{id}", name="suppresion_ville")
     */
    public function suppression(Ville $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();

        $this->addFlash(
            'success',
            "La ville de " . $user->getNom() . " a ete supprimer avec success"
        );
        return $this->redirectToRoute('admin_ville');
    }

    /**
     * @Route("/ville/importation", name="importation_ville")
     */
    public function importationVille(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ImportationVilleType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fichier = $form->get("fichier")->getData();

            $chemin = $fichier->getPathName();

            $reader = ReaderEntityFactory::createXLSXReader();

            //on lis le fichier
            $reader->open($chemin);
            //lecture de fichier
            $excelTabDonnee = [];
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $excelTabDonnee[] = $row->toArray();
                }
            }

            //Maitenant importation des donnes
            for ($i = 0; $i < count($excelTabDonnee); $i++) {

                $ville = new Ville();

                $ville->setNom($excelTabDonnee[$i][0]);

                $em->persist($ville);
            }

            $this->addFlash(
                'message',
                'vous avez importer avec succes votre fichier excel'
            );

            $em->flush();

            return $this->redirectToRoute('admin_ville');
        }

        return $this->render('admin/ville/importation.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    // /**
    //  * @Route("/ville/exportation", name="exportation_ville")
    //  */
    // public function exportation(Request $request, EntityManagerInterface $em): Response
    // {
    //     $form = $this->createForm(ExportationVilleType::class);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $fichier = $form->get("fichier")->getData();

    //         $chemin = $fichier->getFileName();

    //         $writer = WriterEntityFactory::createXLSXWriter();

    //         $writer->openToFile($chemin);


    //         $cells = [
    //             WriterEntityFactory::createCell('carl'),
    //             WriterEntityFactory::createCell('is'),
    //             WriterEntityFactory::createCell('great')
    //         ];

    //         // $singleRow = WriterEntityFactory::createRow($cells);

    //         // $writer->addRow($singleRow);

    //         $multipleRow = [
    //             WriterEntityFactory::createRow($cells),
    //         ];

    //         $writer->addRows($multipleRow);
    //         // dd($writer->addRows($multipleRow));

    //         $writer->close();
    //     }


    //     return $this->render('admin/ville/exportation.html.twig', [
    //         'form' => $form->createView(),

    //     ]);
    // }

    // /**
    //  * @Route("/ville/exportation", name="exportation_ville")
    //  */
    // public function exportationExcel(Request $request, EntityManagerInterface $em): Response
    // {
    //     $form = $this->createForm(ExportationVilleType::class);

    //     $form->handleRequest($request);


    //     return $this->render('admin/ville/exportation.html.twig', [
    //         'form' => $form->createView(),

    //     ]);
    // }
}
