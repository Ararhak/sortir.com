<?php


namespace App\Controller;


use App\Entity\Event;
use App\Form\EventType;
use App\Service\EventManager;
use App\Service\PossibleActionsOfMemberOnEventManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EditEvent extends AbstractController
{

    /**
     * @Route("/edit-my-event/{id}", name="edit_my_event", requirements={"id": "\d+"})
     */

    public function editMyEvent(EntityManagerInterface $em, Request $request, $id)
    {

        $event = $em->getRepository(Event::class)->find($id);
        $possibleActions = new PossibleActionsOfMemberOnEventManager($em);
        $userCanModifyEvent = $possibleActions->userCanModifyEvent($this->getUser()->getId(), $id);

        if (!$userCanModifyEvent) {
            $this->addFlash(
                'error',
                'Vous ne pouvez pas modifier cet événement, seul l\'organisateur est autorisé à le faire'
            );

            return $this->redirectToRoute('display_events');
        }


        //Creer le formulaire d'édition de l'événement, le rendre et l'envoyer dans la twig
        $eventForm = $this->createForm(EventType::class, $event);
        $eventFormView = $eventForm->createView();
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid() ) {

            $eventManager = new EventManager($em);
            $event = $eventManager->setAttributesToEventFromFormAndReturn($event, $this->getUser(), $eventForm);

            $em->persist($event);
            $em->flush();

            $this->addFlash('success', 'Événement modifié avec succès !');
            return $this->redirectToRoute('display_events');
        }


        return $this->render(
            'displayevents/edit_event.html.twig',
            compact(
                'userCanModifyEvent',
                'eventFormView'
            )
        );


    }

}