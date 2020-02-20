<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ImportCsvController extends AbstractController
{


    /**
     * @Route("/admin/import-users-csv", name="import_user_csv");
     * */
    public function importUserCSV(EntityManagerInterface $entityManager)
    {


//        if( ($fileCSV = fopen( '../assests/data/listeMembres.csv','r')) !== false){
        if(file_exists ('assests/data/listeMembres.csv')){

            dump('file open');
        }
        else{
            dump('error');
        }





        die();

        return $this->redirectToRoute('app_login');
    }

}