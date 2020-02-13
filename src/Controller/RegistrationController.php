<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\RegistrationFormType;
use App\Security\LoginFromAuthentificator;
use App\Service\DefaultPasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{


    //TODO: cette route n'est accessible qu'avec une ROOLE_ADMIN
    //L'admin remplit un formulaire basique (nom, prenom, email) et c'est a l'utilisateur de se connecter
    //Changer son mot de passe qui a été mis par défaut (premiere lettre du prénom + nom: ex: pschuhmacher)

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFromAuthentificator $authenticator): Response
    {
        $user = new Member();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {
            //Build default password
            $plainPassword  = DefaultPasswordGenerator::defaultPasswordFromNameAndSurname(
                $form->get('name')->getData(),
                $form->get('surname')->getData()
            );

            $user->setPassword( $passwordEncoder->encodePassword( $user,  $plainPassword));
            $user->setActive(false);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash('success', 'Le membre a bien été inscrit');

            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
