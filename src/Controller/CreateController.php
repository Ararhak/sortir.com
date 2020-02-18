<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Status;
use App\Form\EventType;
use App\Service\DurationUnit;
use App\Service\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CreateController extends AbstractController
{
    /**
     * @Route("/create", name="create")
     */
    public function create(EntityManagerInterface $entityManager, Request $request)
    {
        $event = new Event();

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $eventForm = $this->createForm(EventType::class, $event);
            $eventForm->handleRequest($request);

            if ($eventForm->isSubmitted() && $eventForm->isValid() ) {

                $eventManager = new EventManager($entityManager);
                $event = $eventManager->setAttributesToEventFromFormAndReturn($event, $this->getUser(), $eventForm);

                $entityManager->persist($event);
                $entityManager->flush();

                $this->addFlash('success', 'Événement ajouté !');
                return $this->redirectToRoute('display_events');
            }

            return $this->render('create/create.html.twig', ['eventFormView' => $eventForm->createView()]);

        } else {
            return $this->redirectToRoute('app_login');
        }
    }

}
