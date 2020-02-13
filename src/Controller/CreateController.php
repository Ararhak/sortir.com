<?php

namespace App\Controller;


use App\Entity\Event;
use App\Entity\Member;
use App\Entity\Status;
use App\Form\EventType;
use App\Form\MyProfileType;
use Doctrine\ORM\EntityManagerInterface;
use http\Message;
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

            if ($eventForm->isSubmitted() && $eventForm->isValid()) {
                $this->saveInDB($event, $user, $entityManager);
                return $this->redirectToRoute('display_events');
            }


            return $this->render('create/create.html.twig', ['eventFormView' => $eventForm->createView()]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    public function saveInDB($event, $user, EntityManagerInterface $entityManager){
        $event->setSite($user->getsite());
        $this->addFlash('success', 'Événement ajouté !');
        $status = $entityManager->getRepository(Status::class)->findByLibel(Status::opened());
        $event->setStatus($status);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($event);
        $entityManager->flush();
        return $this->redirectToRoute('home'); //TODO : Route's name
    }

    public function verifyDate($event){

    }



}
