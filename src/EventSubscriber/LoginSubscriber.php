<?php

namespace App\EventSubscriber;

use App\Entity\HistoriqueConnexion;
use App\Repository\HistoriqueConnexionRepository;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    private HistoriqueConnexionRepository $historiqueConnexionRepo;
    private RequestStack $requestStack;

    public function __construct(HistoriqueConnexionRepository $historiqueConnexionRepository, RequestStack $reqtStack)
    {
        $this->historiqueConnexionRepo = $historiqueConnexionRepository;
        $this->requestStack = $reqtStack;
    }

    public function onSecurityAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        /**
         * @var User
         */

        $user = $event->getAuthenticationToken()->getUser();
        $ip = $this->requestStack->getCurrentRequest()->server->get("REMOTE_ADDR");
        // $tempMis = $this->requestStack->getCurrentRequest()->server->get("REQUEST_TIME");
        $nom = $user->getNom();
        $prenom = $user->getPrenom();

        $historiqueConnexion = new HistoriqueConnexion();

        $historiqueConnexion->setDateConnexion(new DateTime())
            ->setIp($ip)
            ->setNom($nom)
            ->setPrenom($prenom)
            // ->setTempsMis($tempMis)
            ;

        $this->historiqueConnexionRepo->add($historiqueConnexion, true);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'security.authentication.success' => 'onSecurityAuthenticationSuccess',
        ];
    }
}
