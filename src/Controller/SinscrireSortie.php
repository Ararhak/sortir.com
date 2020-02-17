<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\Status;
use App\Service\Inscription;
use App\Service\InscriptionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SinscrireSortie extends AbstractController
{

    /**
     * @Route ("/register-to-event/{id}", name="register_to_event", requirements={"id": "\d+"})
     */
    public function registerToEvent(EntityManagerInterface $em, $id)
    {

        //Il faut refaire tous les tests fait dans la twig:
        $event = $em->getRepository(Event::class)->find($id);
        $inscriptionManager = new InscriptionManager($em);


        if ($inscriptionManager->userCanRegisterToEvent($this->getUser(), $event)) {
            $event->addRegistered($this->getUser());
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Votre inscription a bien été enregistrée ! ');
        } else {
            $this->addFlash('error', 'Impossible de s\'inscrire à l\'événement, il n\' y a plus de places disponibles');
        }

        return $this->redirectToRoute('display_events');
    }


}