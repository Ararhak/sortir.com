<?php


namespace App\Service;


use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;

class EventManager
{

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }


    public function setAttributesToEventFromFormAndReturn($event, $user, $eventForm)
    {

        //Set organizer:
        $event->setOrganizer($user);

        //Set dateTime
        $event = $this->buildDateTimeFromDateAndTimeForm($eventForm, $event);

        //Set duration
        $event->setDuration(
            DurationUnit::convertDurationIntoHours(
                $eventForm->get('duration')->getData(),
                $eventForm->get('duration_unit')->getData()
            )
        );

        //Set site
        $event->setSite($user->getsite());

        $status = $this->em->getRepository(Status::class)->findByLibel(Status::created());

        //Set status
        $event->setStatus($status);

        return $event;
    }

    public function buildDateTimeFromDateAndTimeForm($eventForm, $event)
    {

        //merge date et time starting date:
        $startingDate = $eventForm->get('startingDate')->getData();
        $startingTime = $eventForm->get('startingTime')->getData();
        $startingDateTime = $event->buildDateTimeFromStringDateStringTime($startingDate, $startingTime);

        //merge date et time deadline :
        $deadLineDate = $eventForm->get('deadLineDate')->getData();
        $deadLineTime = $eventForm->get('deadLineTime')->getData();
        $inscriptionDeadLine = $event->buildDateTimeFromStringDateStringTime($deadLineDate, $deadLineTime);

        $event->setStartingDateTime($startingDateTime);
        $event->setInscriptionDeadLine($inscriptionDeadLine);

        return $event;
    }


    //Gestion des status:

    //Update status to $status if not already equal to it
    //TODO: add new constraint based on different status and current date (and return error)
    public function publishEvent($event)
    {

        if($event->getStatus()->getLibel() !== Status::opened()){
            $status = $this->em->getRepository(Status::class)->findByLibel(Status::opened());
            $event->setStatus($status);
        }

        return $event;
    }

}