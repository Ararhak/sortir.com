<?php


namespace App\Service;


use App\Entity\Event;
use App\Entity\Status;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;

class UpdateEventStatus
{

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function updateEventStatussssss()
    {


        //$event = $this->em->getRepository(Event::class)->find($id);
        $events = $this->em->getRepository(Event::class)->findAllEventExceptStatusArchived();

        $eventTab = [];

        foreach ($events as $event) {

            //The event have created status ?
            $createdEvent = $event->getStatus()->getLibel() === Status::created();
            //The event have opened status ?
            $openedEvent = $event->getStatus()->getLibel() === Status::opened();
            //The event have ongoing status ?
            $ongoingEvent = $event->getStatus()->getLibel() === Status::ongoing();
            //The event have closed status ?
            $closedEvent = $event->getStatus()->getLibel() === Status::closed();
            //The event have finished status ?
            $finishedEvent = $event->getStatus()->getLibel() === Status::finished();
            //The event have cancelled status ?
            $cancelledEvent = $event->getStatus()->getLibel() === Status::cancelled();
            //The event have archived status ?
            $archivedEvent = $event->getStatus()->getLibel() === Status::archived();


            //l'evt est-il closed ? (passage du statut opened à closed)
            $nowEventIsClosed = $event->getInscriptionDeadLine() < new \DateTime('now');
            //l'evnt est-il commencé ? (pour passage statut opened à ongoing)
            $nowEventIsStarted = $event->getStartingDateTime() < new \DateTime('now');

            //duration
            $duration = $event->getDuration();

            //l'evnt est-il terminé ? (passage du statut ongoing à finished)
            $startingDateTimeClone = clone $event->getStartingDateTime();
            $nowEventIsFinished = false;
            $nowEventIsArchived = false;
            try {
                $nowEventIsFinished = ($startingDateTimeClone->add(new DateInterval('PT' . $duration . 'H')) < new \DateTime('now'));
            } catch (\Exception $e) {
            }
            //l'evnt est-il archivé ? (passage du statut finished à archived)
            $startingDateTimeClone = clone $event->getStartingDateTime();
            try {
                $nowEventIsArchived = ($startingDateTimeClone->add(new DateInterval('PT' . ($duration + 630) . 'H')) < new \DateTime('now'));
            } catch (\Exception $e) {
            }

            //Passer du statut opened à closed
//        if ($openedEvent && $nowEventIsClosed) {
//            $statusClosed = $this->em->getRepository(Status::class)->findByLibel(Status::closed());
//            $event->setStatus($statusClosed);
//        }
//        //Passer du statut closed à ongoing
//        if($closedEvent && $nowEventIsStarted){
//            $statusOnGoing = $this->em->getRepository(Status::class)->findByLibel(Status::ongoing());
//            $event->setStatus($statusOnGoing);
//        }

            //Passer du statut opened à ongoing quand l'évennement commence
            if ($openedEvent && $nowEventIsStarted) {
                $statusOnGoing = $this->em->getRepository(Status::class)->findByLibel(Status::ongoing());
                $event->setStatus($statusOnGoing);
            } //Passer du statut ongoing à finished
            else if ($ongoingEvent && $nowEventIsFinished) {
                $statusFinished = $this->em->getRepository(Status::class)->findByLibel(Status::finished());
                $event->setStatus($statusFinished);
            } //Passer du statut finished à archived
            else if ($finishedEvent && $nowEventIsArchived) {
                $statusArchived = $this->em->getRepository(Status::class)->findByLibel(Status::archived());
                $event->setStatus($statusArchived);
            }

            $eventTab = $event;

        }
        //pour les sorties annulées, les garder cancelled et juste les enlever de l'affichage après 30 jours

        $this->em->persist($eventTab);
        $this->em->flush();

    }

    public function updateEventStatus2()
    {
        $events = $this->em->getRepository(Event::class)->findAllEventExceptStatusArchivedAndCreated();

        foreach ($events as $event) {

            $this->updateEvent($event);

        }

        $this->em->flush();
    }

    public function updateEvent($event)
    {
        $startingDate = $event->getStartingDateTime();
        $duration = $event->getDuration();

        if ($this->eventIsArchived($startingDate, $duration)) {
            $statusArchived = $this->em->getRepository(Status::class)->findByLibel(Status::archived());
            $event->setStatus($statusArchived);
        } elseif ($this->eventIsFinished($startingDate, $duration)) {
            $statusFinished = $this->em->getRepository(Status::class)->findByLibel(Status::finished());
            $event->setStatus($statusFinished);
        } elseif ($this->eventIsOnGoing($startingDate, $duration)) {
            $statusOnGoing = $this->em->getRepository(Status::class)->findByLibel(Status::ongoing());
            $event->setStatus($statusOnGoing);
        }

        $this->em->persist($event);

    }


    function eventIsArchived($startingDate, $duration)
    {

        $startingDateClone = clone $startingDate;

        try {
            $nowEventIsArchived = ($startingDateClone->add(new DateInterval('PT' . ($duration + 630) . 'H')) < new \DateTime('now'));
        } catch (\Exception $e) {
        }

        return $nowEventIsArchived;

    }

    function eventIsFinished($startingDate, $duration)
    {

        $startingDateClone = clone $startingDate;

        try {
            $nowEventIsFinished = ($startingDateClone->add(new DateInterval('PT' . $duration . 'H')) < new \DateTime('now'));
        } catch (\Exception $e) {
        }

        return $nowEventIsFinished;

    }

    function eventIsOnGoing($startingDate)
    {

        return $startingDate < new \DateTime('now');
    }


}