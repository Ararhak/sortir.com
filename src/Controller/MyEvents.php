<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MyEvents extends AbstractController
{

    /**
     * @Route("/my-events", name="my_events");
     * */
    public function myEvent(EntityManagerInterface $em)
    {

        //Get my created events
        $events = $em->getRepository(Event::class)->findCreatedEvent($this->getUser()->getId());

        //Status: created
        $drafts = array();
        //Status: opened, ongoing, closed
        $active = array();
        //Status: finished, cancelled, archived
        $inactive = array();

        foreach ($events as $event) {

            $statusLibel = $event->getStatus()->getLibel();

            if ($statusLibel === Status::created()) {
                $drafts[] = $event;
            } elseif ($statusLibel === Status::opened() || $statusLibel === Status::ongoing(
                ) || $statusLibel === Status::closed()) {
                $active[] = $event;
            } else {
                $inactive[] = $event;
            }
        }

        return $this->render(
            "displayevents/manage_my_events.html.twig",
            compact(
                'drafts',
                'active',
                'inactive'
            )
        );
    }


}