<?php


namespace App\Controller;


use App\Entity\Event;
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
        $myEvents = $em->getRepository(Event::class)->findCreatedEvent($this->getUser()->getId());


        return $this->render("displayevents/manageMyEvents.html.twig",compact('myEvents'));


    }



}