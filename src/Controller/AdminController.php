<?php


namespace App\Controller;


use App\Entity\Member;
use App\Form\RegistrationFormType;
use App\Security\LoginFromAuthentificator;
use App\Service\DefaultPasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class AdminController extends  AbstractController
{

    /**
     * @Route("/admin/register", name="app_register")
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

        return $this->render('admin/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/users-manager", name="app_manage_users")
     */
    public function manageUsers(EntityManagerInterface $em){

        $members = $em->getRepository(Member::class)->findall();
        return $this->render('admin/users_manager.html.twig',compact('members'));

    }


}