<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\Member;
use App\Entity\Site;
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

class AdminController extends AbstractController
{

    /**
     * @Route("/admin/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFromAuthentificator $authenticator
    ): Response {
        $user = new Member();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Build default password
            $plainPassword = DefaultPasswordGenerator::defaultPasswordFromNameAndSurname(
                $form->get('name')->getData(),
                $form->get('surname')->getData()
            );

            $user->setPassword($passwordEncoder->encodePassword($user, $plainPassword));
            $user->setActive(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash('success', 'Le membre a bien été inscrit');

            return $this->redirectToRoute('app_register');
        }

        return $this->render(
            'admin/register.html.twig',
            [
                'registrationForm' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/users-manager", name="app_manage_users")
     */
    public function manageUsers(EntityManagerInterface $em)
    {

        $members = $em->getRepository(Member::class)->findall();

        return $this->render('admin/users_manager.html.twig', compact('members'));

    }


    /**
     * @Route("/admin/cancel-evenet/{id}", name="app_cancel_event")
     */
    public function cancelEvent(EntityManagerInterface $em, $id)
    {

        $event = $em->getRepository(Event::class)->find($id);
        $em->remove($event);
        $em->flush();

        $this->addFlash('success', 'La sortie a bien été supprimée');

        return $this->redirectToRoute('display_events');

    }


    /**
     * @Route("/admin/register-from-csv-file/", name="app_register_from_csv")
     */
    public function registerFromFileCSV(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, $data)
    {

        //Check for errors
        if (count($data) == 0 || count($data) == 1 || is_null($data)) {
            $this->addFlash('success', 'Aucun membre dans la liste à insérer');
            return $this->redirectToRoute('display_events');
        }

        //Remove header (ie first lign):
        $newMembers = array_slice($data, 1);

        $errors = array();

        foreach ($newMembers as $member) {

            $name = $member[0];
            $surname = $member[1];
            $email = $member[2];
            $siteName = $member[3];

            //Check si le membre existe deja dans la base de données (email seulement car pas de pseudo encore)
            if ($em->getRepository(Member::class)->findByEmail($email)) {
                //Existe déjà:
                $errors[] = ['member' => $member, 'msg' => $email. ': email existe déjà dans la base, il n\'a pas été inséré.'];
                continue;
            }


            $user = new Member();
            $user->setName($name);
            $user->setSurname($surname);
            $user->setMail($email);
            $plainPassword = DefaultPasswordGenerator::defaultPasswordFromNameAndSurname($name, $surname);
            $user->setPassword($passwordEncoder->encodePassword($user, $plainPassword));
            $user->setActive(true);

            //Set site:
            if( !is_null($siteUser = $this->getSite($em, $siteName)) ){
                $user->setSite($siteUser);
            }
            else{
                $errors[] = ['member' => $member, 'msg' => $name. ' : site renseignée inconnu, il n\'a pas été inséré.'];
                continue;
            }

            //Persist:
            $em->persist($user);
            $this->addFlash('error', $name . ' : inscrit avec succès !');
        }

        $em->flush();
        dump($errors);

        //Display errors:
        foreach ($errors as $error){
            $this->addFlash('error', $error['msg']);
        }

        return $this->redirectToRoute('display_events');

    }


    //set user site if site exists in db, return user, false otherwise
    public function getSite(EntityManagerInterface $em, $siteName){

        $site = $em->getRepository(Site::class)->findByName($siteName);
        if(!is_null($site)){
            return $site;
        }
        return null;
    }



}