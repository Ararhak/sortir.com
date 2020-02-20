<?php

namespace App\Controller;


use App\Entity\ProfilePictureName;
use App\Form\MyProfileType;
use App\Form\ProfilePictureFileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

        $oldprofilepicture = null;

        if (!is_null($user->getPicture())) {
            $profilepicture = $user->getPicture();
            $oldprofilepicture = clone $profilepicture;
        } else {
            $profilepicture = new ProfilePictureName();
        }
        $profilepictureForm = $this->createForm(ProfilePictureFileType::class, $profilepicture);
        $profilepictureForm->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        if ($memberForm->isSubmitted()) {

            if ($memberForm->isValid()) {

                $this->addFlash('success', 'Le profil a bien été modifié');
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('display_events');
            } else {
                $entityManager->refresh($user);
            }
        }

        if ($profilepictureForm->isSubmitted() && $profilepictureForm->isValid()) {
            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $profilepictureForm->get('profilepicturename')->getData();

            // this condition is needed because the 'picture' field is not required
            // so the JPG/PNG file must be processed only when a file is uploaded
            if ($profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate(
                    'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                    $originalFilename
                );
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();

                // Move the file to the directory where pictures are stored
                try {
                    $profilePictureFile->move(
                        $this->getParameter('profilepic_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'profilePictureFilename' property to store the PDF file name
                // instead of its contents
                $profilepicture->setName($newFilename);

                $this->addFlash('success', 'La photo de profil a bien été modifié');
                $entityManager->persist($profilepicture);

                $this->getUser()->setPicture($profilepicture);
                //If previous picture, remove old link
                if($oldprofilepicture){
                    unlink($this->getParameter('profilepic_directory').'/'.$oldprofilepicture->getName());
                }

                $entityManager->flush();

                return $this->redirectToRoute('edit_my_profile');
            }
        }


        return $this->render(
            'profile/edit_my_profile.html.twig',
            [
                'myProfileFormView' => $memberForm->createView(),
                'porfilePictureFormView' => $profilepictureForm->createView(),
                'user' => $user,
            ]
        );
    }


}
