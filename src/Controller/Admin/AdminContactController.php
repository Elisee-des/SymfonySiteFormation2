<?php

namespace App\Controller\Admin;

use App\Form\ContactType;
use App\Form\SMSContactType;
use Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminContactController extends AbstractController
{
    /**
     * @Route("/admin/contact", name="admin_contact")
     */
    public function contact(): Response
    {
        return $this->render('admin/contact/index.html.twig', []);
    }

    /**
     * @Route("/admin/contact/email", name="admin_contact_email")
     */
    public function smsContact(Request $request): Response
    {
        /**
         * @var admin
         */
        $admin = $this->getUser();
        $form = $this->createForm(ContactType::class);

        $contact = $form->handleRequest($request);

        // $emailTo = $admin->getEmail();
        // $name = $admin->getNom();
        $emailTo =  $contact->get('email')->getData();
        $sujet = $contact->get('sujet')->getData();
        $message = $contact->get('message')->getData();
        $nom = $contact->get('nom')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request);
            $email = new Mail();
            $email->send($emailTo, $nom, $sujet, $message);

            $this->addFlash(
                'message',
                "Votre email a bien ete envoyez. Nous vous contacterons bientot"
            );

            // return $this->redirectToRoute('admin_contact');
        }

        return $this->render('admin/contact/email.html.twig', [
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/contact/sms", name="admin_contact_sms")
     */
    public function contactEmail(Request $request): Response
    {
        /**
         * @var admin
         */
        $form = $this->createForm(SMSContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = $request->get('sms_contact')['message'];
            $numeroDestinateur = $request->get('sms_contact')['numero'];

            $url = "https://www.aqilas.com/api/v1/sms";

            $data = [
                "from" => "Sabidani",
                "to" => [$numeroDestinateur],
                "text" => $message
            ];
            $dataJson = json_encode($data);
            $header = [
                'Content-Type: application/json',
                "X-AUTH-TOKEN: bca6852f-1703-4417-bac4-411712e3e5b0"
            ];

            $apiUrl = curl_init($url);
            curl_setopt($apiUrl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($apiUrl, CURLOPT_POSTFIELDS, $dataJson);
            curl_setopt($apiUrl, CURLOPT_RETURNTRANSFER, true);

            curl_exec($apiUrl);

            curl_close($apiUrl);

            $this->addFlash(
                'message',
                'Votre sms a ete envoyez avec success'
            );

            // return $this->redirectToRoute('admin_contact_sms');
        }

        return $this->render('admin/contact/sms.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
