<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\MyProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class EditProfile extends AbstractController
{
    /**
     * @Route("/edit-my-profile", name="edit_my_profile")
     */
    public function myProfile(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        $memberForm = $this->createForm(MyProfileType::class, $user);

        $memberForm->handleRequest($request);

        if($memberForm->isSubmitted() && $memberForm->isValid()){

            $this->addFlash('success', 'Le profil a bien été modifié');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('display_profile_member', array('id' => $user->getid()));
        }

        return $this->render('profile/edit_my_profile.html.twig',
            [
            'myProfileFormView' => $memberForm->createView(),

        ]);
    }


}
