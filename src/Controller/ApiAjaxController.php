<?php


namespace App\Controller;

use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiAjaxController extends AbstractController
{

    /**
     * @Route("/api", name="api_ajax");
     * */
    public function apiHome(){
        return new JsonResponse( array("Hello, welcome on the API of Sortir.com !"));
    }

    /**
     * @Route("/api/getlocations/like/{location_pattern}", name="api_ajax_locations_like");
     * */
    public function getLocation(EntityManagerInterface $entityManager, $location_pattern)
    {
        //Find all locations matching location_pattern
        $locationsMatching = $entityManager->getRepository(Location::class)->findbyLocationNameMatching($location_pattern);

        $response = array();

        for($i = 0 ; $i !== count($locationsMatching); $i++){
            $jsonLocation = array (
                'id' => $locationsMatching[$i]->getId(),
                'name' => $locationsMatching[$i]->getName(),
                'street' =>  $locationsMatching[$i]->getStreet(),
                'city' => $locationsMatching[$i]->getCity()->getName(),
                'zipCode' => $locationsMatching[$i]->getCity()->getZipCode()
            );
            $response[] = $jsonLocation;
        }

        return new JsonResponse( $response );
    }


}