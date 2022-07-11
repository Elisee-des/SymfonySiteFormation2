<?php

namespace App\Controller\User;

use App\Form\SMSContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SMSContactController extends AbstractController
{
    /**
     * @Route("/utilisateur/contact", name="utilisateur_contact")
     */
    public function contact(): Response
    {
        return $this->render('user/contact/index.html.twig', []);
    }

    /**
     * @Route("/utilisateur/contact/sms", name="utilisateur_contact_sms")
     */
    public function smsEmail(Request $request): Response
    {
        /**
         * @var User
         */
        $form = $this->createForm(SMSContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $message = $request->get('sms_contact')['message'];
            $numeroDestinateur = $request->get('sms_contact')['numero'];

            $url="https://www.aqilas.com/api/v1/sms";

            $data=[
                "from"=>"Sabidani",
                "to"=>[$numeroDestinateur],
                "text"=>$message
            ];
            $dataJson = json_encode($data);
            $header=[
                'Content-Type: application/json',
                "X-AUTH-TOKEN: bca6852f-1703-4417-bac4-411712e3e5b0"
            ];
            
            $apiUrl=curl_init($url);
            curl_setopt($apiUrl,CURLOPT_HTTPHEADER,$header);
            curl_setopt($apiUrl,CURLOPT_POSTFIELDS,$dataJson);
            curl_setopt($apiUrl,CURLOPT_RETURNTRANSFER,true);
    
            curl_exec($apiUrl);
    
            curl_close($apiUrl);

            $this->addFlash(
               'message',
               'Votre sms a ete envoyez avec success. Nous vous contacterons sous peu'
            );

            return $this->redirectToRoute('utilisateur_contact_sms');
        }

        return $this->render('user/contact/sms.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
