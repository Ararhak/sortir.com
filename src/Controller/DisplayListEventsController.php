<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\Site;
use App\Service\UpdateEventStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

//        $updateOneEvent = new UpdateEventStatus($entityManager);
//        $updateOneEvent->updateEventStatus(2);

        return $this->render("displayevents/displayevents.html.twig",compact('events','sites'));
    }

    /**
     * @Route("/displayevents/filtered", name="display_events_by_form_parameters")
     */
    public function displayEventsbyFormParameters(EntityManagerInterface $entityManager, Request $request){
        $id = $this->getUser()->getid();
        $site = $request->query->get('site');
        if(!empty($request->query->get('dateStart'))){
        $dateStart = new \DateTime($request->query->get('dateStart'));
        } else {
            $dateStart="";
        }
        if(!empty($request->query->get('dateDeadline'))) {
            $dateDeadline = new \DateTime($request->query->get('dateDeadline'));
        } else {
            $dateDeadline="";
        }
        $organizer = $request->query->get('organizer');
        $registered = $request->query->get('registered');
        $unregistered = $request->query->get('unregistered');
        $finished = $request->query->get('finished');
        $eventRepo = $entityManager->getRepository(Event::class);
        $events = $eventRepo->findEventByFormParameters($site, $dateStart, $dateDeadline, $organizer, $registered, $unregistered, $finished, $id, $this->getUser());
        return $this->render("includes/eventslist.html.twig",compact('events'));
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