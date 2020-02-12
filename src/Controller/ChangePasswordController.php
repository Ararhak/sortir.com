<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AbstractController
{
    /**
     * @Route("/changepassword", name="changePassword")
     */
    public function changePassword(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $changePasswordForm = $this->createForm(ChangePasswordType::class, $user);

        $changePasswordForm->handleRequest($request);

        if($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()){
            $this->addFlash('success', 'Mot de passe modifié');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }



        return $this->render('change_password/changePassword.html.twig', ['ChangePasswordFormView'=> $changePasswordForm->createView(),]);
    }
}
