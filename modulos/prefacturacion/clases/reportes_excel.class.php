<?php
/* 
 * Clase donde se realizaran la generacion de archivos en excel.
 * Julian Alvaran 
 * Techno Soluciones SAS
 */
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Color;

use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

if(file_exists("../../../modelo/php_conexion.php")){
    include_once("../../../modelo/php_conexion.php");
}

class TS_Excel extends conexion{
    
    public function reporte_entrega_excel($datos_empresa,$mipres_id) {
        require_once('../../../librerias/Excel/PHPExcel2.php');
        
        $objPHPExcel = new Spreadsheet();
        
        $Campos=["A","B","C","D","E","F","G","H","I","J","K","L","M",
                 "N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
                 "AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM",
                 "AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ",
                 "BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM",
                 "BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ"
            
                ];
    $db=$datos_empresa["db"];    
    $datos_entrega=$this->DevuelveValores("$db.mipres_entrega", "ID", $mipres_id);
    $datos_direccionamiento=$this->DevuelveValores("$db.mipres_direccionamiento", "ID", $mipres_id);
    $datos_eps=$this->DevuelveValores("$db.salud_eps", "cod_pagador_min", $datos_direccionamiento["CodEPS"]);
    $sql="SELECT * FROM $db.prefactura_paciente WHERE TipoDocumento='$datos_direccionamiento[TipoIDPaciente]' AND NumeroDocumento='$datos_direccionamiento[NoIDPaciente]'";
    $datos_paciente=$this->FetchAssoc($this->Query($sql));
    $nombre_servicio="TRANSPORTE EN AMBULANCIA";
    if($datos_entrega["CodSerTecEntregado"]==151){
        $nombre_servicio="TRANSPORTE NO AMBULANCIA";
    }
    
    $styleTitulo = [
        
            'font' => [
                'bold' => true,
                'size' => 16
            ]
        ];
    $styleSubTitulo = [
        'font' => [
            'bold' => true,
            'size' => 11
        ],
        "borders" => array(
        "outline" => array(
            "borderStyle" => Border::BORDER_THIN,
            
            "color" => array("argb" => "000000"),
            ),
        ),
        'fill' => array(
            'fillType' => Fill::FILL_SOLID,
            'startColor' => array('argb' => 'e5eff6')
        )
        
    ];
    
    $styleBorders = [
        
        "borders" => array(
        "outline" => array(
            "borderStyle" => Border::BORDER_THIN,
            
            "color" => array("argb" => "000000"),
            ),
        )
        
    ];
    
    $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleTitulo);
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1","REPORTE DE ENTREGA DE TECNOLOGÍAS EN SALUD NO PBS");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A2","PLATAFORMA MIPRES");    
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:J1');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:J2');
    
    $objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("A2:J2")->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('E4:G4')->getAlignment()->setHorizontal('center');
    
    $objPHPExcel->getActiveSheet()->getStyle('C4:D4')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('C4:J4')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('H4:I4')->applyFromArray($styleSubTitulo);
    
    $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle("A6:D6")->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('E6')->applyFromArray($styleTitulo);
    $objPHPExcel->getActiveSheet()->getStyle("E6:J6")->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('A6:J6')->applyFromArray($styleBorders);
    
    $objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('A9')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('C9')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('F9')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('A10')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('F10')->applyFromArray($styleSubTitulo);
    
    $objPHPExcel->getActiveSheet()->getStyle('A11')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('F11')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('A13')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('A14')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('F14')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('F15')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('A16')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('D16')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('H16')->applyFromArray($styleSubTitulo);
    
    $objPHPExcel->getActiveSheet()->getStyle('A18')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('A19')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('B19')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('E19')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('H19')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('I19')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('J19')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('A28')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('A29')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('B29')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('D29')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('G29')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('I29')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('A36')->applyFromArray($styleSubTitulo);
    $objPHPExcel->getActiveSheet()->getStyle('F36')->applyFromArray($styleSubTitulo);
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C4","Fecha de Entrega");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E4",$datos_entrega["FecEntrega"]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H4","Número de entrega");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J4",$datos_entrega["NoEntrega"]);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C4:D4');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E4:G4');
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A6","Datos del Proveedor-Dispensador");
    $objPHPExcel->setActiveSheetIndex(0)->getRowDimension('6')->setRowHeight(43);
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E6",$datos_empresa["RazonSocial"]);
    $objPHPExcel->getActiveSheet()->getStyle('E6:J6')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('A6:J6')->getAlignment()->setVertical('center');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A6:D6');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E6:J6');
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A8","Datos del paciente");
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A8:j8');
    $objPHPExcel->getActiveSheet()->getStyle('A8:j8')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('A8:j8')->applyFromArray($styleBorders);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A9","Tipo ID");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B9",$datos_entrega["TipoIDPaciente"]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C9","Número ID");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D9",$datos_entrega["NoIDPaciente"]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F9","EPS");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G9",$datos_eps["cod_pagador_min"]." - ".$datos_eps["sigla_nombre"]);
    $objPHPExcel->setActiveSheetIndex(0)->getRowDimension('9')->setRowHeight(25.5);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G9:J9');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D9:E9');
    $objPHPExcel->getActiveSheet()->getStyle('G9:J9')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('D9:D9')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('G9:J9')->getAlignment()->setVertical('center');
    $objPHPExcel->getActiveSheet()->getStyle('D9:D9')->getAlignment()->setVertical('center');
    $objPHPExcel->getActiveSheet()->getStyle('A9:J9')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('D9')->applyFromArray($styleTitulo);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A10","1 Nombre");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B10",$datos_paciente["PrimerNombre"]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F10","1 Apellido");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G10",$datos_paciente["PrimerApellido"]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A11","2 Nombre");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B11",$datos_paciente["SegundoNombre"]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F11","2 Apellido");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G11",$datos_paciente["SegundoApellido"]);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B10:E10');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B11:E11');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G10:J10');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G11:J11');
    $objPHPExcel->getActiveSheet()->getStyle('A10:J10')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('A11:J11')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('B10:E10')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('G10:J10')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('B11:E11')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('G11:J11')->getAlignment()->setHorizontal('center');
    
    //DATOS DE LA PREESCRIPCION
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A13","Datos del paciente");
    $objPHPExcel->getActiveSheet()->getStyle('A13:j13')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('A13:j13')->applyFromArray($styleBorders);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A13:j13');
    $objPHPExcel->getActiveSheet()->getStyle('A8:j8')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('A8:j8')->applyFromArray($styleBorders);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A14","Número de prescripción");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C14",$datos_entrega["NoPrescripcion"]);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A14:B15');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C14:E15');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F14","Fecha prescripción");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H14", substr($datos_direccionamiento["FecDireccionamiento"], 0, 10));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("f15","Fecha direccionamiento");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H15",$datos_direccionamiento["FecDireccionamiento"]);
    
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H14:j14');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H15:j15');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('f14:g14');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('f15:g15');
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("a16","ID traza");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("b16", $datos_entrega["ID"]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("d16","ID direccionamiento");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("f16",$datos_direccionamiento["IDDireccionamiento"]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("h16","ID Entrega");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("i16",$datos_entrega["IDEntrega"]);
    
    $objPHPExcel->getActiveSheet()->getStyle('c14:e14')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('h14:j14')->getAlignment()->setHorizontal('center');
    
    $objPHPExcel->getActiveSheet()->getStyle('c14:e14')->getAlignment()->setVertical('center');
    $objPHPExcel->getActiveSheet()->getStyle('h15:j15')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('a14:j14')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('a15:j15')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('h14:j14')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('h15:j15')->applyFromArray($styleBorders);
    
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('b16:c16');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('f16:g16');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('i16:j16');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('d16:e16');
    $objPHPExcel->getActiveSheet()->getStyle('b16:c16')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('f16:g16')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('i16:j16')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('d16:e16')->applyFromArray($styleBorders);
    
    $objPHPExcel->getActiveSheet()->getStyle('c14')->applyFromArray($styleTitulo);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
    
   //datos de la tecnologia
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("a18","Datos de la tecnología");
    $objPHPExcel->getActiveSheet()->getStyle('a18:j18')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('A18:j18')->applyFromArray($styleBorders);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A18:j18');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("a19","Código");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("b19","Nombre");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("e19","Presentación");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("h19","Lote");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("i19","Cód. interno");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("j19","Cant.");
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(10)->setWidth('6');
    $objPHPExcel->getActiveSheet()->getStyle('a19:j19')->getAlignment()->setHorizontal('center');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('b19:d19');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('e19:g19');
    $objPHPExcel->getActiveSheet()->getStyle('a19:j19')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('b19:d19')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('e19:f19')->applyFromArray($styleBorders);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("a20",$datos_entrega["CodSerTecEntregado"]);
  
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("b20",$nombre_servicio);
      
    
     
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("e20",$nombre_servicio);
    
    //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('h20:j20');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("j20",$datos_entrega["CantTotEntregada"]);
    $objPHPExcel->getActiveSheet()->getStyle('a20:j20')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('a20:j20')->getAlignment()->setVertical('center');
    for($i=20;$i<=26;$i++){
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('b'.$i.':d'.$i.'');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('e'.$i.':g'.$i.'');
        $objPHPExcel->getActiveSheet()->getStyle('a'.$i.':j'.$i.'')->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('b'.$i.':d'.$i.'')->applyFromArray($styleBorders); 
        $objPHPExcel->getActiveSheet()->getStyle('e'.$i.':g'.$i.'')->applyFromArray($styleBorders); 
        $objPHPExcel->getActiveSheet()->getStyle('h'.$i.'')->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('i'.$i.'')->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('j'.$i.'')->applyFromArray($styleBorders); 
        $objPHPExcel->setActiveSheetIndex(0)->getRowDimension($i)->setRowHeight(29);
    }
    //Datos de quien recibe
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("a28","Datos de quien recibe");
    $objPHPExcel->getActiveSheet()->getStyle('a28:j28')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('A28:j28')->applyFromArray($styleBorders);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A28:j28');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("a29","Tipo ID");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("b29","Número ID");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("d29","Nombres y apellidos");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("g29","Parentesco");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("i29","Teléfono");
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('b29:c29');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('d29:f29');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('g29:h29');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('i29:j29');
    $objPHPExcel->getActiveSheet()->getStyle('b29:c29')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('d29:f29')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('g29:h29')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('i29:j29')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('a29:j29')->getAlignment()->setHorizontal('center');
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("a30",$datos_paciente["TipoDocumento"]);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("b30",$datos_paciente["NumeroDocumento"]);
    $nombre_paciente=$datos_paciente["PrimerNombre"]." ".$datos_paciente["SegundoNombre"]." ".$datos_paciente["PrimerApellido"]." ".$datos_paciente["SegundoApellido"];
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("d30", str_replace("  ", " ", $nombre_paciente));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("g30","Paciente");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("i30",$datos_paciente["Telefono"]);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('b30:c30');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('d30:f30');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('g30:h30');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('i30:j30');
    $objPHPExcel->getActiveSheet()->getStyle('A30')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('b30:c30')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('d30:f30')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('g30:h30')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('i30:j30')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('a30:j30')->getAlignment()->setHorizontal('center');
    $objPHPExcel->getActiveSheet()->getStyle('a30:j30')->getAlignment()->setVertical('center');
    $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(30)->setRowHeight(33);
    
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("a36","Firma de quien recibe");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("f36","Firma de quien entrega");
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('a36:e36');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('f36:j36');
    $objPHPExcel->getActiveSheet()->getStyle('a36:e36')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('f36:j36')->applyFromArray($styleBorders);
    
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('a37:e37');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('f37:j37');
    $objPHPExcel->getActiveSheet()->getStyle('a37:e37')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('f37:j37')->applyFromArray($styleBorders);
    $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(37)->setRowHeight(74.25);
    
    $objPHPExcel->getActiveSheet()
            ->getPageMargins()->setTop(0.75);
        $objPHPExcel->getActiveSheet()
            ->getPageMargins()->setRight(0.2);
        $objPHPExcel->getActiveSheet()
            ->getPageMargins()->setLeft(0.2);
        $objPHPExcel->getActiveSheet()
            ->getPageMargins()->setBottom(0.75);
    
//Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("www.technosoluciones.com.co")
        ->setLastModifiedBy("www.technosoluciones.com.co")
        ->setTitle("reporte de entrega mipres")
        ->setSubject("Formato")
        ->setDescription("Documento generado por Techno Soluciones SAS")
        ->setKeywords("techno soluciones sas")
        ->setCategory("reporte de entrega mipres");    
    $nombre_archivo="entrega-$mipres_id";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'."$nombre_archivo".'.xls"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter=IOFactory::createWriter($objPHPExcel,'Xlsx');
    $objWriter->save('php://output');
    exit; 
   
    }
    
   
   //Fin Clases
}
    