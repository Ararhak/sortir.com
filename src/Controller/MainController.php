<?php

namespace App\Controller;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home");
     * */
    public function myProfile(EntityManagerInterface $entityManager)
    {
        $eventRepo = $entityManager->getRepository(Event::class);
        $events = $eventRepo->findAll();
        return $this->render("displayevents/displayevents.html.twig",compact('events'));
    }
}
