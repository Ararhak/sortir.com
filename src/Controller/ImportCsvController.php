<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ImportCsvController extends AbstractController
{


    /**
     * @Route("/admin/import-users-csv/{file_to_import}", name="import_user_csv");
     * */
    public function importMembersFromCSV(EntityManagerInterface $entityManager, $file_to_import = 'listeMembres.csv')
    {

        $pathToFile = $this->getParameter('data_csv_directory').'/'.$file_to_import;

        if (!$this->fileToImportExist($pathToFile)) {
            $this->addFlash('error', 'Impossible de trouver le ficher à importer!');
            return $this->redirectToRoute('display_events');
        }

        $data = $this->loadCSVtoArray($pathToFile);

        if (!$data) {
            $this->addFlash('error', 'Erreur dans l importation du fichier, verifier le format!');
            return $this->redirectToRoute('display_events');
        }

        dump('loaded ok');
        die();

        //Send data to AdminController route 'app_register_from_csv'

    }


    public function fileToImportExist($pathToFile)
    {
        return file_exists($pathToFile);
    }


    public function loadCSVtoArray($pathToFile)
    {
        if (file_exists($pathToFile)) {

            $dataALL = array();

            $nbFieldsExpectedAtEachRow = 4;

            if (($handlerCSV = fopen($pathToFile, 'r')) !== false) {

                while ( ($data = fgetcsv($handlerCSV, 500, ",") ) !== false) {

                    $num = count($data);

                    $dataALL[] = $data;

                    if (count($data) !== $nbFieldsExpectedAtEachRow) {
                        return false;
                    }

                }
                fclose($handlerCSV);
            }

            return $dataALL;
        }
        else{
            return false;
        }
    }
}