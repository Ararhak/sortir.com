<?php

namespace App\Controller;

use App\Service\DefaultPasswordGenerator;
use http\Env\Request;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FirstConnexion extends AbstractController
{

    /**
     * @Route("/firstlogin", name="first_login");
     * */
    public function isFirstLogin()
    {
        //TODO: test if first connexion (ie default password), if it is redirect to the page, else redirect to home

        $userName = $this->getUser()->getName();
        $userSurname = $this->getUser()->getSurname();
        //Build default password
        $defaultPassword = DefaultPasswordGenerator::defaultPasswordFromNameAndSurname($userName, $userSurname);


        //Compare to password
        if ($this->getUser()->getPassword() === $defaultPassword) {
            return $this->render('Main/home.html.twig');
        } else {
            return $this->render("security/firstlogin.html.twig");
        }
    }

}