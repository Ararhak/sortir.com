<?php


namespace App\Service;

use App\Entity\Event;
use App\Entity\Member;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;


class PossibleActionsOfMemberOnEventManager
{

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    //Return true if a user can register to event, false otherwise
    public function userCanRegisterToEvent($idUser, $idEvent)
    {

        $event = $this->em->getRepository(Event::class)->find($idEvent);
        $user = $this->em->getRepository(Member::class)->find($idUser);

        //Est ce que la sortie est publiée?
        $eventIsPublished = $event->getStatus()->getLibel() === Status::opened();

        //Est ce que les inscriptions sont ouvertes? (ouvertes par defaut a la publication (status opened) de l'evenement)
        $eventIsOpenedToInscription = $event->getInscriptionDeadLine() > new \DateTime('now');

        //Est ce que l'on est deja inscrit a l'event
        $userIsAlreadyRegisteredToEvent = $event->getRegisteredMembers()->contains($user);

        //Est ce qu'il reste de la place
        $eventHasEmptySlots = $event->getRegisteredMembers()->count() < $event->getNbMaxRegistration();

        $userCanRegisterToEvent =
            $eventIsPublished
            && $eventIsOpenedToInscription
            && $eventHasEmptySlots
            && !$userIsAlreadyRegisteredToEvent;


//        dump($eventIsPublished,$eventIsOpenedToInscription,$eventHasEmptySlots,!$userIsAlreadyRegisteredToEvent);
//        die();

        return $userCanRegisterToEvent;
    }

    //Return true if a user can cancel an event, false otherwise
    public function userCanCancelEvent($idUser, $idEvent)
    {

        $event = $this->em->getRepository(Event::class)->find($idEvent);
        $user = $this->em->getRepository(Member::class)->find($idUser);

        $isOpened = $event->getStatus()->getLibel() === Status::opened();
        $isCreated = $event->getStatus()->getLibel() === Status::created();
        $userIsOrganizer = $event->getOrganizer()->getId() === $user->getId();


        $userCanCancelEvent = ($isOpened || $isCreated) && $userIsOrganizer;

        return $userCanCancelEvent;

    }

    //Return true if a user can modify an event ( organizer and event not opened yet), else otherwise
    public function userCanModifyEvent($idUser, $idEvent){

        $event = $this->em->getRepository(Event::class)->find($idEvent);
        $user = $this->em->getRepository(Member::class)->find($idUser);

        $isNotOpenedYet = $event->getStatus()->getLibel() !== Status::opened();
        $userIsOrganizer = $event->getOrganizer()->getId() === $user->getId();

        return $isNotOpenedYet && $userIsOrganizer;

    }


    public function userCanPublishEvent($idUser, $idEvent){

        $event = $this->em->getRepository(Event::class)->find($idEvent);
        $user = $this->em->getRepository(Member::class)->find($idUser);

        $isCreated = $event->getStatus()->getLibel() === Status::created();
        $userIsOrganizer = $event->getOrganizer()->getId() === $user->getId();

        return $isCreated && $userIsOrganizer;

    }


}