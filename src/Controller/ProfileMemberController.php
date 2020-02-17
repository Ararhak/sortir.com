<?php


namespace App\Controller;

use App\Entity\Member;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileMemberController extends  AbstractController
{

    /**
     * @Route("/profile-member/{id}", name="display_profile_member")
     */
    public function displayProfile(EntityManagerInterface $em, $id){
        $user = $em->getRepository(Member::class)->find($id);
        return $this->render("profile/profileMember.html.twig", compact('user'));
    }


}