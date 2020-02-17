<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Status;
use App\Form\CancelOneEventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CancelOneEventController extends AbstractController
{
    /**
     * @Route("/canceloneevent/{id}", name="cancel_one_event", requirements={"id": "\d+"})
     */
    public function cancelOneEvent(EntityManagerInterface $entityManager, $id, Request $request)
    {
        $eventRepository = $entityManager->getRepository(Event::class);
        $eventDetail = $eventRepository->find($id);

        $isOpened = $eventDetail->getStatus()->getLibel() === Status::opened();
        $isCreated = $eventDetail->getStatus()->getLibel() === Status::created();

        $cancelForm = $this->createForm(CancelOneEventType::class, $eventDetail);
        $cancelFormView = $cancelForm->createView();

        $cancelForm->handleRequest($request);

        if($cancelForm->isSubmitted() && $cancelForm->isValid()){
            $this->addFlash('success', 'La sortie a été annulée');

            $eventDetail->setStatus(Status::cancelled());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($eventDetail);
            $entityManager->flush();

            return $this->redirectToRoute('display_events');

        }


        return $this->render('displayevents/cancelOneEvent.html.twig', compact('eventDetail', 'isOpened', 'isCreated', 'cancelFormView')
            );
    }
}
