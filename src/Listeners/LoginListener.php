<?php

namespace App\Listeners;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;


class LoginListener implements EventSubscriberInterface
{

    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function onSuccessfulLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (!$user->getNbConnection()) {
            $user->setNbConnection(1);
        }
        else{
            $user->setNbConnection($user->getNbConnection() + 1);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function onFailLogin()
    {
        //TODO
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            SecurityEvents::INTERACTIVE_LOGIN => 'onSuccessfulLogin',
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onFailLogin',
        );
    }
}