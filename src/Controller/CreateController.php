<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Status;
use App\Form\EventType;
use App\Service\DurationUnit;
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

            $user = $this->getUser();
            $event->setOrganizer($user);

            $eventForm = $this->createForm(EventType::class, $event);
            $eventForm->handleRequest($request);

            if ($eventForm->isSubmitted() && $eventForm->isValid() ) {

                $event = $this->setAttributes($event, $eventForm, $entityManager);
                $this->persistEvent($event);
                return $this->redirectToRoute('display_events');
            }

            return $this->render('create/create.html.twig', ['eventFormView' => $eventForm->createView()]);

        } else {
            return $this->redirectToRoute('app_login');
        }
    }


    public function setAttributes($event, $eventForm, $entityManager ){

        //Set dateTime
        $event = $this->buildDateTimeFromDateAndTimeForm($eventForm, $event);

        //Set duration
        $event->setDuration(DurationUnit::convertDurationIntoHours(
            $eventForm->get('duration')->getData(),
            $eventForm->get('duration_unit')->getData()
        ));
        //Set site
        $event->setSite($this->getUser()->getsite());

        //TODO : status a mettre Status::created() !
        $status = $entityManager->getRepository(Status::class)->findByLibel(Status::opened());

        //Set status
        $event->setStatus($status);

        return $event;
    }

    public function buildDateTimeFromDateAndTimeForm($eventForm, $event){

        //merge date et time starting date:
        $startingDate = $eventForm->get('startingDate')->getData();
        $startingTime = $eventForm->get('startingTime')->getData();
        $startingDateTime = $event->buildDateTimeFromStringDateStringTime($startingDate,$startingTime );

        //merge date et time deadline :
        $deadLineDate = $eventForm->get('deadLineDate')->getData();
        $deadLineTime = $eventForm->get('deadLineTime')->getData();
        $inscriptionDeadLine = $event->buildDateTimeFromStringDateStringTime($deadLineDate, $deadLineTime );

        $event->setStartingDateTime( $startingDateTime );
        $event->setInscriptionDeadLine( $inscriptionDeadLine );

        return $event;
    }

    public function persistEvent($event)
    {
        $this->addFlash('success', 'Événement ajouté !');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($event);
        $entityManager->flush();
        //return $this->redirectToRoute('display_events'); //TODO : Route's name
    }


}
