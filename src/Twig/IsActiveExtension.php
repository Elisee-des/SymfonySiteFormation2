<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IsActiveExtension extends AbstractExtension
{

    private $requestStact;

    public function __construct(RequestStack $requestStck)
    {
        $this->requestStact = $requestStck;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('isActive1', [$this, 'isActive1'])
        ];
    }

    public function isActive1($lien = []): string
    {

        $routeActuel = $this->requestStact->getCurrentRequest()->get("_route");

       if (in_array($routeActuel, $lien)) {

           return "active";
       }else return "";

    }
}