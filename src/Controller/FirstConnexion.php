<?php

namespace App\Controller;

use App\Service\DefaultPasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FirstConnexion extends  AbstractController
{

    /**
     * @Route("/firstlogin", name="first_login");
     * */

    public function isFirstLogin()
    {
        //TODO: test if first connexion (ie default password), if it is redirect to the page, else redirect to home

        $userPassword = $this->getUser()->getPassword();

        $userName = $this->getUser()->getName();
        $userSurname = $this->getUser()->getSurname();

        //Build default password
        $defaultPassword = DefaultPasswordGenerator::defaultPasswordFromNameAndSurname($userName,$userSurname);

        //Compare to password

        dump($defaultPassword,$userPassword );
        die();

        return $this->render("security/firstlogin.html.twig");
    }

}