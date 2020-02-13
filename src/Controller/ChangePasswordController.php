<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\User;

class ChangePasswordController extends AbstractController
{
    /**
     * @Route("/changepassword", name="changePassword")
     */

    public function changePassword(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $user = $this->getUser();

        $changePasswordForm = $this->createForm(ChangePasswordType::class, $user);

        $changePasswordForm->handleRequest($request);

        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {

            //TODO : verifier toutes les contraintes de taille de caractères spéciaux, pas le meme que l'ancien

            //encode the password give in the form
            $this->getUser()->setPassword(
                $passwordEncoder->encodePassword(
                    $this->getUser(),
                    $changePasswordForm->get('password')->getData()
                )
            );
            
            $this->addFlash('success', 'Mot de passe modifié avec succès');
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'change_password/changePassword.html.twig',
            ['ChangePasswordFormView' => $changePasswordForm->createView(),]
        );
    }
}
