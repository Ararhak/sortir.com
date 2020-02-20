<?php


namespace App\Controller;


use App\Form\UploadMembersFromCSVFileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImportCsvController extends AbstractController
{

    /**
     * @Route("/admin/upload-users-csv/", name="upload_user_csv");
     * */
    public function uploadMembersFromCSV(EntityManagerInterface $entityManager, Request $request ){

        $formUploadCSV = $this->createForm(UploadMembersFromCSVFileType::class);
        $formUploadCSV->handleRequest($request);

        if($formUploadCSV->isSubmitted() && $formUploadCSV->isValid()){


            $csvFile = $formUploadCSV->get('csvFile')->getData();

            if($csvFile){

                $originalFileName = pathinfo($csvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFileName);
                $newFilename = $safeFilename.'-'.uniqid().'.csv';

                dump($newFilename);

                // Move the file to the directory where data are stored
                try {
                    $csvFile->move(
                        $this->getParameter('data_csv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

            }

            //Passer $newFilename a la fonction
            $this->importMembersFromCSV($entityManager, $newFilename);
        }


        return $this->render(
            'admin/upload_csv.html.twig',
            [
                'formUploadCSV' => $formUploadCSV->createView(),
            ]
        );

    }


    /**
     * @Route("/admin/import-users-csv/{file_to_import}", name="import_user_csv_file");
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

        $response = $this->forward('App\Controller\AdminController:registerFromFileCSV', ['data' => $data]);
        return $response;
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