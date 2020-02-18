<?php

namespace App\Controller;


use App\Form\MyProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Validator\Validator\ValidatorInterface;


class EditProfile extends AbstractController
{
    /**
     * @Route("/edit-my-profile", name="edit_my_profile")
     */
    public function myProfile(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $user = $this->getUser();

        $memberForm = $this->createForm(MyProfileType::class, $user);

        $memberForm->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        if($memberForm->isSubmitted()){

            if($memberForm->isValid()){

                $this->addFlash('success', 'Le profil a bien été modifié');
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('display_events');
            }
            else{
                $entityManager->refresh($user);
            }
        }

        return $this->render('profile/edit_my_profile.html.twig',
            [
            'myProfileFormView' => $memberForm->createView(),
        ]);
    }



}
