<?php

namespace App\Controller;

use App\Entity\Event;
use App\Service\PossibleActionsOfMemberOnEventManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DisplayOneEventController extends AbstractController
{
    /**
     * @Route("/{id}", name="display_one_event", requirements={"id": "\d+"})
     */
    public function displayOneEvent(EntityManagerInterface $entityManager, $id)
    {
        $eventDetail = $entityManager->getRepository(Event::class)->find($id);
        $possibleActions = new PossibleActionsOfMemberOnEventManager($entityManager);

        $userCanWithdrawEvent = $possibleActions->userCanWithdrawEvent($this->getUser()->getId(), $eventDetail->getId());
        $userCanRegisterToEvent = $possibleActions->userCanRegisterToEvent($this->getUser()->getId(), $eventDetail->getId());
        $userCanCancelEvent = $possibleActions->userCanCancelEvent($this->getUser()->getId(), $id);
        $userCanModifyEvent = $possibleActions->userCanModifyEvent($this->getUser()->getId(), $eventDetail->getId());
        $userCanPublishEvent = $possibleActions->userCanPublishEvent($this->getUser()->getId(), $eventDetail->getId());

        return $this->render(
            'displayevents/displayOneEvent.html.twig',
            compact(
                'eventDetail',
                'userCanRegisterToEvent',
                'userCanModifyEvent',
                'userCanCancelEvent',
                'userCanWithdrawEvent',
                'userCanPublishEvent'
            )
        );


    }
}
