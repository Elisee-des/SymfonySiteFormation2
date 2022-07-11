<?php

namespace App\Controller\User;

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
        /**
         * @var User
         */
        $user = $this->getUser();
        $form = $this->createForm(ContactType::class);

        $contact = $form->handleRequest($request);

        // $emailTo = $user->getEmail();
        // $name = $user->getNom();
        $emailTo =  $contact->get('email')->getData();
        $sujet = $contact->get('sujet')->getData();
        $message = $contact->get('message')->getData();
        $nom = $contact->get('nom')->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            $email = new Mail();
            $email->send($emailTo, $nom, $sujet, $message);

            $this->addFlash(
                'message',
                "Votre email a bien ete envoyez. Nous vous contacterons bientot"
            );

            // return $this->redirectToRoute('utilisateur_contact');
        }



        return $this->render('user/contact/email.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
