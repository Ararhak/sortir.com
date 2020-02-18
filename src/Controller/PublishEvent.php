<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\Status;
use App\Service\EventManager;
use App\Service\PossibleActionsOfMemberOnEventManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PublishEvent extends AbstractController
{

    /**
     * @Route("/publish-my-event/{id}", name="publish_my_event", requirements={"id": "\d+"})
     * */
    public function home(EntityManagerInterface $entityManager, $id)
    {

        $event = $entityManager->getRepository(Event::class)->find($id);
        $possibleActions = new PossibleActionsOfMemberOnEventManager($entityManager);
        $userCanPublishEvent = $possibleActions->userCanPublishEvent($this->getUser()->getId(), $event->getId());


        if (!$userCanPublishEvent) {
            $this->addFlash(
                'error',
                'Vous ne pouvez pas publier cet événement'
            );

            return $this->redirectToRoute('display_events');
        }

        $eventManager = new EventManager($entityManager);
        $event = $eventManager->publishEvent($event);
        $entityManager->persist($event);
        $entityManager->flush();

        $this->addFlash('success', 'Événement publié avec succès !');
        return $this->redirectToRoute('display_events');

    }

}