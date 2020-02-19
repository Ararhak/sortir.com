<?php

namespace App\Controller;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home");
     * */
    public function home(EntityManagerInterface $entityManager)
    {
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/contact", name="contact");
     * */
    public function contact()
    {
        return $this->render('Main/contact.html.twig');
    }


}
