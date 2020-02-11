<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/myProfile", name ="myProfile");
     */
    public function myProfile()
    {
        return $this->render('home.html.twig');
    }

}