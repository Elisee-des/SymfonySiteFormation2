<?php

namespace App\Controller\Admin;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefautController extends AbstractController
{
    private $directory;

    private $excel_directory;

    public function __construct($excel_directory)
    {
        $this->directory = $excel_directory;
    }

    // /**
    //  * @Route("/ville/exportation", name="admin_exportation_ville")
    //  */
    // public function index()
    // {
    //     $spreadsheet = new Spreadsheet();

    //     /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
    //     $sheet = $spreadsheet->getActiveSheet();
    //     $sheet->setCellValue('A2', 'Hello World !');
    //     $sheet->setTitle("My seconde Worksheet");

    //     // Create your Office 2007 Excel (XLSX Format)
    //     $writer = new Xlsx($spreadsheet);

    //     // In this case, we want to write the file in the public directory
    //     $publicDirectory = $this->directory;
    //     // e.g /var/www/project/public/my_first_excel_symfony4.xlsx
    //     $excelFilepath =  $publicDirectory . '/my_seconde_excel_symfony4.xlsx';

    //     // Create the file
    //     $writer->save($excelFilepath);

    //     // Return a text response to the browser saying that the excel was succesfully created
    //     return new Response("Excel generated succesfully");
    // }

    /**
     * @Route("/ville/exportation", name="admin_exportation_ville")
     */
    public function index()
    {
        $spreadsheet = new Spreadsheet();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');

        //Adding data to the excel sheet
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'This')
            ->setCellValue('A2', 'is')
            ->setCellValue('A3', 'only')
            ->setCellValue('A4', 'an')
            ->setCellValue('A5', 'example');

        $spreadsheet->getActiveSheet()
            ->setCellValue('B1', "You")
            ->setCellValue('B2', "can")
            ->setCellValue('B3', "download")
            ->setCellValue('B4', "this")
            ->setCellValue('B5', "library")
            ->setCellValue('B6', "on")
            ->setCellValue('B7', "https://php-download.com/package/phpoffice/phpspreadsheet");


        $spreadsheet->getActiveSheet()
            ->setCellValue('C1', 1)
            ->setCellValue('C2', 0.5)
            ->setCellValue('C3', 0.25)
            ->setCellValue('C4', 0.125)
            ->setCellValue('C5', 0.0625);

        $spreadsheet->getActiveSheet()
            ->setCellValue('C6', '=SUM(C1:C5)');
        $spreadsheet->getActiveSheet()
            ->getStyle("C6")->getFont()
            ->setBold(true);


        $writer = IOFactory::createWriter($spreadsheet, "Xlsx"); //Xls is also possible
        $writer->save("my_excel_file.xlsx");

        // // Create your Office 2007 Excel (XLSX Format)
        // $writer = new Xlsx($spreadsheet);

        // // In this case, we want to write the file in the public directory
        // $publicDirectory = $this->directory;
        // // e.g /var/www/project/public/my_first_excel_symfony4.xlsx
        // $excelFilepath =  $publicDirectory . '/my_thirst_excel_symfony4.xlsx';

        // // Create the file
        // $writer->save($excelFilepath);

        // Return a text response to the browser saying that the excel was succesfully created
        return new Response("Excel generated succesfully");
    }
}
