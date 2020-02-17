<?php

namespace App\Controller;

use App\Entity\Event;

use App\Entity\Status;
use App\Service\Inscription;
use App\Service\InscriptionManager;
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
        $InscriptionManager = new InscriptionManager($entityManager);
        $userCanRegisterToEvent = $InscriptionManager->userCanRegisterToEvent($this->getUser()->getId(), $eventDetail->getId());

        $isOpened = $eventDetail->getStatus()->getLibel() === Status::opened();



        return $this->render('displayevents/displayOneEvent.html.twig', compact('eventDetail', 'isOpened', 'userCanRegisterToEvent'));

    }
}
