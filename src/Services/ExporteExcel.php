<?php

  /** PHPExcel */
  require_once '../Classes/PHPExcel.php';

  /** PHPExcel_IOFactory */
  require_once '../Classes/PHPExcel/IOFactory.php';

//   //Create new PHPExcel object
//   $objPHPExcel = new PHPExcel();

//   //Set properties
//   $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
//                                ->setLastModifiedBy("Maarten Balliauw")
//                                ->setTitle("Office 2007 XLSX Test Document")
//                                ->setSubject("Office 2007 XLSX Test Document")
//                                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
//                                ->setKeywords("office 2007 openxml php")
//                                ->setCategory("Test result file");

//   $result = 'select * from table1';

//   for($i=0;$i<count($result);$i++) {

//     $result1 = 'select * from table2 where table1_id = ' . $result[$i]['table1_id'];

//     for ($j=0;$j<count($result1);$j++) {
//       $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $j, $result1[$j]['name']);
//     }

//     //Set active sheet index to the first sheet, so Excel opens this as the first sheet
//     $objPHPExcel->setActiveSheetIndex(0);

//     //Save Excel 2007 file
//     $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//     $objWriter->save(str_replace('.php', '.xlsx', __FILE__));

//     //Echo done
//     echo date('H:i:s') . " Done writing file.\r\n";       
//   }

?>