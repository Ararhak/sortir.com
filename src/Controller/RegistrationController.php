<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\RegistrationFormType;
use App\Security\LoginFromAuthentificator;
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

        if ($form->isSubmitted() && $form->isValid()) {


            //Build default password with first letter of surname and name (lower case)
            $plainPassword = substr($form->get('surname')->getData(),0, 1) . $form->get('name')->getData();

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $plainPassword
                )
            );

            $user->setActive(false);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
