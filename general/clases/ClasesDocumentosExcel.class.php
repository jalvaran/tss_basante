<?php
/* 
 * Clase donde se realizaran la generacion de archivos en excel.
 * Julian Alvaran 
 * Techno Soluciones SAS
 */
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

if(file_exists("../../modelo/php_conexion.php")){
    include_once("../../modelo/php_conexion.php");
}

class TS_Excel extends conexion{
    
    // Clase para generar excel de un balance de comprobacion
    
    public function exportar_tabla($tabla,$sql,$nombres_columnas) {
        require_once('../../librerias/Excel/PHPExcel2.php');
        
        $objPHPExcel = new Spreadsheet();
        
        $Campos=["A","B","C","D","E","F","G","H","I","J","K","L","M",
                 "N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
                 "AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM",
                 "AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ",
                 "BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM",
                 "BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ"
            
                ];
        $z=0;
        $i=1;
        
        foreach ($nombres_columnas as $key => $value) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($Campos[$z++].$i,$value);
        }
        
        
        $Consulta=$this->Query($sql);
        $i=1;
        while($datos_consulta= $this->FetchAssoc($Consulta)){
            $z=0;
            $i++;
            foreach ($datos_consulta as $key => $value) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($Campos[$z++].$i,$datos_consulta[$key]);
            }
         
        }
        
   
   //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("www.technosoluciones.com.co")
        ->setLastModifiedBy("www.technosoluciones.com.co")
        ->setTitle($tabla)
        ->setSubject("Formato")
        ->setDescription("Documento generado por Techno Soluciones SAS")
        ->setKeywords("techno soluciones sas")
        ->setCategory($tabla);    
 
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'."$tabla".'.xls"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter=IOFactory::createWriter($objPHPExcel,'Xlsx');
    $objWriter->save('php://output');
    exit; 
   
    }
    
   
   //Fin Clases
}
    