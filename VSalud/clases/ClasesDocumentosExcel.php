<?php
/* 
 * Clase donde se realizaran la generacion de informes.
 * Julian Alvaran
 * Techno Soluciones SAS
 */
//include_once '../../modelo/php_tablas.php';
class TS5_Excel extends Tabla{
    
    // Clase para generar excel de un balance de comprobacion
    
    public function GenerarRelacionCobrosPrejuridicos($idCobro,$Vector) {
        require_once '../../librerias/Excel/PHPExcel.php';
        
        $objPHPExcel = new PHPExcel();  
        
        $objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode('#');
        $objPHPExcel->getActiveSheet()->getStyle('D:F')->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle("A:F")->getFont()->setSize(10);
        
        $f=1;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[1].$f,"RELACION DE FACTURAS COBRO No. $idCobro")
                        
            ;
        $f=2;
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,"FACTURA")
            ->setCellValue($this->Campos[1].$f,"FECHA DE RADICADO")
            ->setCellValue($this->Campos[2].$f,"NUMERO DE RADICADO")
            ->setCellValue($this->Campos[3].$f,"VALOR")
            
            
            ;
            
        $sql="SELECT f.`num_factura` as NumFactura,`fecha_radicado`,`numero_radicado`,`valor_neto_pagar` "
           . "FROM `salud_archivo_facturacion_mov_generados` f "
           . "INNER JOIN salud_cobros_prejuridicos_relaciones r ON r.num_factura=f.num_factura "
           . "WHERE r.idCobroPrejuridico=$idCobro  ";
        $Consulta=$this->obCon->Query($sql);
                
        $Total=0;
        $f=3;
        while($DatosFacturas= $this->obCon->FetchArray($Consulta)){
            
            $Total=$Total+$DatosFacturas["valor_neto_pagar"];
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,$DatosFacturas["NumFactura"])
            ->setCellValue($this->Campos[1].$f,$DatosFacturas["fecha_radicado"])
            ->setCellValue($this->Campos[2].$f,$DatosFacturas["numero_radicado"])
            ->setCellValue($this->Campos[3].$f,$DatosFacturas["valor_neto_pagar"])
                        
            ;
            $f++;
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,"")
            ->setCellValue($this->Campos[1].$f,"")
            ->setCellValue($this->Campos[2].$f,"TOTALES")
            ->setCellValue($this->Campos[3].$f,$Total)
                     
            ;
   $objPHPExcel->
    getProperties()
        ->setCreator("www.technosoluciones.com.co")
        ->setLastModifiedBy("www.technosoluciones.com.co")
        ->setTitle("Relacion de Facturas")
        ->setSubject("Informe")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("techno soluciones sas")
        ->setCategory("Relacion de Facturas");    
 
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'."Relacion_Facturas_Cobro_$idCobro".'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
    $objWriter->save('php://output');
    exit; 
   
    }
   
   //Fin Clases
}
    