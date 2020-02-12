<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChangePasswordController extends AbstractController
{
    /**
     * @Route("/changepassword", name="changePassword")
     */
    public function changePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {

        //require the user to log during the session, not based on a 'remember_me' cookie
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();


        $changePasswordForm = $this->createForm(ChangePasswordType::class, $user);
        $changePasswordForm->handleRequest($request);


//        $memberRepository = $entityManager->getRepository(Member::class);
//        $member = $memberRepository->find($this->getUser)



        if($changePasswordForm->isSubmitted() && $changePasswordForm->isValid() && $changePasswordForm != null){
            //TODO : verifier toutes les contraintes de taille de caractères spéciaux, pas le meme que l'ancien


            //Saisie current password + hash
            $hashPasswordCapture = $passwordEncoder->encodePassword(
                $user,
                $changePasswordForm->get('currentPassword')->getData()
            );

            $currentUserBDD= $entityManager->getRepository(Member::class)->find($this->getUser()->getId());
            

            if($passwordEncoder->isPasswordValid($currentUserBDD, $hashPasswordCapture)) {


                //encode the password give in the form
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $changePasswordForm->get('password')->getData()
                    )
                );



                $this->addFlash('success', 'Mot de passe modifié');

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('home');

            } else {
                //TODO
                dump($currentUserBDD, $hashPasswordCapture);
                die();
            }


        }



        return $this->render('change_password/changePassword.html.twig', ['ChangePasswordFormView'=> $changePasswordForm->createView(),]);
    }
}
