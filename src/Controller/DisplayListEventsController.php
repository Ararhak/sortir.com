<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DisplayListEventsController extends AbstractController
{
    /**
     * @Route("/displayevents", name="display_events")
     */
    public function displayEvents(EntityManagerInterface $entityManager){
        $site = $this->getUser()->getSite();
        $eventRepo = $entityManager->getRepository(Event::class);
        $sites = $entityManager->getRepository(Site::class)->findAll();
        $events = $eventRepo->findEventBySite($site);
        return $this->render("displayevents/displayevents.html.twig",compact('events','sites'));
    }

    /**
     * @Route("/displayevents/{site}", name="display_events_by_site")
     */
    public function displayEventsbySite(EntityManagerInterface $entityManager, $site){

        $eventRepo = $entityManager->getRepository(Event::class);
        $events = $eventRepo->findEventBySite($site);
        return $this->render("includes/eventslist.html.twig",compact('events'));

    }

}