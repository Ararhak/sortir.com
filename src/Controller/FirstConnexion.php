<?php

namespace App\Controller;

use App\Service\DefaultPasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FirstConnexion extends AbstractController
{

    /**
     * @Route("/firstlogin", name="first_login");
     * */
    public function isFirstLogin()
    {
        if ($this->getUser()->getNbConnection() === 1 ) {
            return $this->render("security/firstlogin.html.twig");
        } else {
            return $this->redirectToRoute('display_events');
        }
    }

}