<?php

namespace App\Controller\User;

use App\Form\ContactType;
use Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/utilisateur/contact", name="utilisateur_contact")
     */
    public function contact(): Response
    {
        return $this->render('user/contact/index.html.twig', []);
    }

}
