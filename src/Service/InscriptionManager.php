<?php


namespace App\Service;

use App\Entity\Event;
use App\Entity\Member;
use App\Entity\Status;
use Doctrine\ORM\EntityManager;


class InscriptionManager
{

    private $em;

    public function __construct(EntityManager $entityManager)
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

        return $userCanRegisterToEvent;
    }

}