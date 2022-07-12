<?php

namespace App\Controller\Admin;

use App\Entity\HistoriqueConnexion;
use App\Form\CategorieType;
use App\Form\EditPasswordUserType;
use App\Form\EditPhotoUserType;
use App\Form\ModificationProfilType;
use App\Repository\CandidatureRepository;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\HistoriqueConnexionRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class HistoriqueConnexionController extends AbstractController
{
    /**
     * @Route("/historique-connexion", name="historique_connexion")
     */
    public function index(HistoriqueConnexionRepository $historiqueConnexionRepository, Request $request): Response
    {
        // $donnees = $historiqueConnexionRepository->findAll();

        $historiqueConnexions = $historiqueConnexionRepository->findAll();
        // $historiqueConnexions = $paginatorInterface->paginate($donnees, $request->query->getInt("page", 1), 20);

        return $this->render("admin/historiqueConnexion/index.html.twig", [
            "historiqueConnexions"=>$historiqueConnexions,
            'admin' => $this->getUser()
        ]);
    }

}
