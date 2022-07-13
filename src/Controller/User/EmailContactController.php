<?php

namespace App\Controller\User;

use App\Form\ContactssType;
use App\Form\ContactType;
use Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailContactController extends AbstractController
{
    /**
     * @Route("/utilisateur/contact/email", name="utilisateur_contact_email")
     */
    public function contactEmail(Request $request): Response
    {
        $form = $this->createForm(ContactssType::class);

        $contact = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prenom = $contact->get("prenom")->getData();
            $email = $contact->get("email")->getData();
            $sujet = $contact->get("sujet")->getData();
            $message = $contact->get("message")->getData() . " ' envoyez par " . $prenom . " a d'adress email: " . $email;

            $email = new Mail();
            $email->sendMailToAdmin($sujet, $message);

            $this->addFlash(
                'success',
                'Votre email a ete envoyez avrc success. nous concterons plutard'
            );

            // var_dump($email);

        }

        return $this->render('user/contact/email.html.twig', [
            "form" => $form->createView(),
            'admin' => $this->getUser()
        ]);
    }
}
