<?php
/*
This call sends a message based on a template.
*/
require 'vendor/autoload.php';

use Mailjet\Client;
use \Mailjet\Resources;

class Mail
{
    private $api_key = "c0d9f349bae53af4eca67c95b6d1e598";
    private $api_key_private = "3de5451f26c6aa9c5c379b3f1d76b350";

    public function sendToAdmin($subject, $message)
    {
        $mj = new Client($this->api_key, $this->api_key_private, true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "yentemasabidani@gmail.com",
                        'Name' => "Yentema"
                    ],
                    'To' => [
                        [
                            'Email' => 'yentemasabidani@gmail.com',
                            'Name' => 'Yentema'
                        ]
                    ],
                    'TemplateID' => 4046162,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'context' => $message
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    }

    public function sendFromAdmin($mailTo, $nom,  $subject, $message)
    {
        $mj = new Client($this->api_key, $this->api_key_private, true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "yentemasabidani@gmail.com",
                        'Name' => "Yentema"
                    ],
                    'To' => [
                        [
                            'Email' => $mailTo,
                            'Name' => $nom
                        ]
                    ],
                    'TemplateID' => 4046162,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'context' => $message
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    }
}
