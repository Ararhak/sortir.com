<?php


namespace App\Controller;


use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DisplayListEventsController extends AbstractController
{
    /**
     * @Route("/displayevents", name="display_events")
     */
    public function displayEvents(EntityManagerInterface $entityManager){
        $eventRepo = $entityManager->getRepository(Event::class);
        $events = $eventRepo->findAll();
        return $this->render("displayevents/displayevents.html.twig",compact('events'));
    }

}