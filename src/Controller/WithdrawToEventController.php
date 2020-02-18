<?php

namespace App\Controller;

use App\Entity\Event;
use App\Service\PossibleActionsOfMemberOnEventManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WithdrawToEventController extends AbstractController
{
    /**
     * @Route("/withdrawtoevent/{id}", name="withdraw_to_event")
     */
    public function withdrawToOneEvent(EntityManagerInterface $entityManager, $id)
    {
        $eventDetail = $entityManager->getRepository(Event::class)->find($id);
        $possibleActions = new PossibleActionsOfMemberOnEventManager($entityManager);

        $userCanWithdrawEvent = $possibleActions->userCanWithdrawEvent($this->getUser()->getId(), $id);


        if ($userCanWithdrawEvent) {
            //enlever le nom du participant de la liste
            $eventDetail->removeRegistered($this->getUser());
            $entityManager->persist($eventDetail);
            $entityManager->flush();
            $this->addFlash('success', 'Votre désistement à été enregistré');
            //ajouter + 1 au compteur
        } else {
            $this->addFlash('error','Votre désistement n\'a pus être pris en compte' );
        }

        return $this->redirectToRoute('display_events');
    }
}
