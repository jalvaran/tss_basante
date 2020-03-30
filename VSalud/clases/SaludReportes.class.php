<?php
/* 
 * Clase donde se realizaran la generacion de informes.
 * Julian Alvaran
 * Techno Soluciones SAS
 */

class Reportes extends conexion{
    /**
     * Se cargan las facturas que se deberan consultar para plasmar las respuestas en un excel
     * @param type $CuentaRIPS
     * @param type $idFactura
     * @param type $idEPS
     * @param type $Vector
     */
    public function CargaFacturasAResponder($CuentaRIPS,$idFactura,$idEPS,$Vector) {
        $Tabla="salud_control_generacion_respuestas_excel";
        $Datos["CuentaRIPS"]=$CuentaRIPS;
        $Datos["idEPS"]=$idEPS;
        $Datos["num_factura"]=$idFactura;
        $sql= $this->getSQLInsert($Tabla, $Datos);
        $this->Query($sql);
        
    }
    
    public function BorrarCarga() {
        
        $directorio="../ArchivosTemporales/Reportes/";
        $files = glob($directorio.'*'); //obtenemos todos los nombres de los ficheros
        foreach($files as $file){
            if(is_file($file))
            unlink($file); //elimino el fichero
        }
        $directorio="../ArchivosTemporales/Reportes/Soportes/";
        $files = glob($directorio.'*'); //obtenemos todos los nombres de los ficheros
        foreach($files as $file){
            if(is_file($file))
            unlink($file); //elimino el fichero
        }
    }
    
    public function CrearArchivoRespuestas($Nombre,$Soportes,$Vector) {
        $this->BorrarCarga();
        $directorio="../ArchivosTemporales/Reportes/";
        
        require_once '../../librerias/Excel/PHPExcel.php';        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->
        getProperties()
            ->setCreator("www.technosoluciones.com.co")
            ->setLastModifiedBy("www.technosoluciones.com.co")
            ->setTitle("Respuestas Glosas")
            ->setSubject("Informe")
            ->setDescription("Documento generado con PHPExcel")
            ->setKeywords("Techno Soluciones SAS")
            ->setCategory("Reportes");    

        
        $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
        $objWriter->save($directorio.$Nombre);
        
    }
    
    public function RegistreRespuestasFacturaExcel($NombreArchivo,$Vector) {
        $Negrilla = array( 'font' => array( 'bold' => true ) ); 
        require_once '../../librerias/Excel/PHPExcel/IOFactory.php';
        
        $NombreArchivo="../ArchivosTemporales/Reportes/".$NombreArchivo;
        
	
	// Creamos un objeto PHPExcel
	$objPHPExcel = new PHPExcel();
        
	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	$objPHPExcel = $objReader->load($NombreArchivo);   
        
	// Indicamos que se pare en la hoja uno del libro
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->getStyle("A:N")->getFont()->setSize(10);
        $sql="SELECT re.idEPS,num_factura,nombre_completo,direccion,telefonos,email FROM salud_control_generacion_respuestas_excel re "
                . "INNER JOIN salud_eps se ON cod_pagador_min=idEPS WHERE re.Generada=0";
        $consulta= $this->Query($sql);
        //$consulta=$this->ConsultarTabla("salud_control_generacion_respuestas_excel", "WHERE Generada=0");
        $i=2;       
        $EncabezadoInforme=1;
        $EncabezadoDatosFacturas=1;
        $EncabezadoFacturas=1;
        $z=0;  
        $Condicion="";
        while($DatosFacturas=$this->FetchArray($consulta)){
            $idFactura=$DatosFacturas["num_factura"];
            $Condicion.=" factura='$idFactura' OR";
        }   
        $Condicion= substr ($Condicion, 0, -2);
            $idFactura=$DatosFacturas["num_factura"];
            $sql="SELECT * FROM vista_salud_respuestas WHERE $Condicion AND (cod_estado=2 or cod_estado=4)";
            $Datos= $this->Query($sql);
            
            
            while($DatosRespuesta= $this->FetchAssoc($Datos)){
                if($EncabezadoInforme==1){
                    $DatosIPS= $this->DevuelveValores("empresapro", "idEmpresaPro", 1);
                    $objPHPExcel->getActiveSheet()->getStyle("D$i")->getFont()->setSize(10)->setBold(true); 
                    $objPHPExcel->getActiveSheet()->SetCellValue("D$i", utf8_encode($DatosIPS["RazonSocial"]));
                    $i++;
                    $objPHPExcel->getActiveSheet()->getStyle("D$i")->getFont()->setSize(10)->setBold(true); 
                    $objPHPExcel->getActiveSheet()->SetCellValue("D$i", $DatosIPS["NIT"]);
                    $i++;
                    $objPHPExcel->getActiveSheet()->getStyle("D$i")->getFont()->setSize(10)->setBold(true); 
                    $objPHPExcel->getActiveSheet()->SetCellValue("D$i", utf8_encode($DatosIPS["Direccion"]));
                    $i++;
                    $objPHPExcel->getActiveSheet()->getStyle("D$i")->getFont()->setSize(10)->setBold(true); 
                    $objPHPExcel->getActiveSheet()->SetCellValue("D$i", utf8_encode($DatosIPS["Telefono"]));
                    $i++;
                    $objPHPExcel->getActiveSheet()->getStyle("D$i")->getFont()->setSize(10)->setBold(true); 
                    $objPHPExcel->getActiveSheet()->SetCellValue("D$i", $DatosIPS["Email"]);
                    $i++;$i++;
                    $objPHPExcel->getActiveSheet()->getStyle("D$i")->getFill() ->setFillType(PHPExcel_Style_Fill::FILL_SOLID) ->getStartColor()->setARGB("8fe5ff");
                   
                    $objPHPExcel->getActiveSheet()->getStyle("D$i")->getFont()->setSize(10)->setBold(true); 
                    $objPHPExcel->getActiveSheet()->SetCellValue("D$i", "INFORME DE RESPUESTAS A GLOSAS");
                    $i++;$i++;
                    $objPHPExcel->getActiveSheet()->getStyle("A$i")->getFont()->setSize(10)->setBold(true); 
                    $objPHPExcel->getActiveSheet()->SetCellValue("A$i", 'NOMBRE ASEGURADORA');
                    $objPHPExcel->getActiveSheet()->SetCellValue("B$i", utf8_encode($DatosFacturas["nombre_completo"]));
                    $i++;
                    $objPHPExcel->getActiveSheet()->getStyle("A$i")->getFont()->setSize(10)->setBold(true);
                    $objPHPExcel->getActiveSheet()->SetCellValue("A$i", 'CÓDIGO MINSALUD');
                    $objPHPExcel->getActiveSheet()->SetCellValue("B$i", $DatosFacturas["idEPS"]);
                    $i++;
                    $objPHPExcel->getActiveSheet()->getStyle("A$i")->getFont()->setSize(10)->setBold(true);
                    $objPHPExcel->getActiveSheet()->SetCellValue("A$i", 'DIRECCIÓN');
                    $objPHPExcel->getActiveSheet()->SetCellValue("B$i", $DatosFacturas["direccion"]);
                    $i++;
                    $objPHPExcel->getActiveSheet()->getStyle("A$i")->getFont()->setSize(10)->setBold(true);
                    $objPHPExcel->getActiveSheet()->SetCellValue("A$i", 'TELÉFONOS');
                    $objPHPExcel->getActiveSheet()->SetCellValue("B$i", $DatosFacturas["telefonos"]);
                    $i++;
                    $objPHPExcel->getActiveSheet()->getStyle("A$i")->getFont()->setSize(10)->setBold(true);
                    $objPHPExcel->getActiveSheet()->SetCellValue("A$i", 'EMAIL');
                    $objPHPExcel->getActiveSheet()->SetCellValue("B$i", $DatosFacturas["email"]);
                    
                    $i++;
                    $EncabezadoInforme=0;
                }
                
                if($EncabezadoFacturas==1){
                    //$i++;$i++;$i++;
                    $Color='ffe6b6';
                    $objPHPExcel->getActiveSheet()->getStyle("A$i:V$i")->getFill() ->setFillType(PHPExcel_Style_Fill::FILL_SOLID) ->getStartColor()->setARGB($Color);
                    $objPHPExcel->getActiveSheet()->getStyle("A$i:V$i")->getFont()->setSize(10)->setBold(true);
                    $objPHPExcel->getActiveSheet()->SetCellValue("A$i", 'NÚMERO DE FACTURA');
                    $objPHPExcel->getActiveSheet()->SetCellValue("B$i", 'FECHA DE FACTURA');
                    $objPHPExcel->getActiveSheet()->SetCellValue("C$i", 'TIPO DE DOCUMENTO');
                    $objPHPExcel->getActiveSheet()->SetCellValue("D$i", 'IDENTIFICACION DEL PACIENTE');
                    $objPHPExcel->getActiveSheet()->SetCellValue("E$i", 'EDAD');
                    $objPHPExcel->getActiveSheet()->SetCellValue("F$i", 'MEDIDA EDAD');
                    $objPHPExcel->getActiveSheet()->SetCellValue("G$i", 'SEXO');
                    $objPHPExcel->getActiveSheet()->SetCellValue("H$i", 'VALOR DE LA FACTURA');
                    $objPHPExcel->getActiveSheet()->SetCellValue("I$i", 'CUENTA RIPS');
                    $objPHPExcel->getActiveSheet()->SetCellValue("J$i", 'RADICADO');
                    //$objPHPExcel->getActiveSheet()->SetCellValue("K$i", 'FACTURA');
                    $objPHPExcel->getActiveSheet()->SetCellValue("K$i", 'CÓDIGO ACTIVIDAD');
                    $objPHPExcel->getActiveSheet()->SetCellValue("L$i", 'VALOR ACTIVIDAD');
                    $objPHPExcel->getActiveSheet()->SetCellValue("M$i", 'FECHA DE RESPUESTA');
                    $objPHPExcel->getActiveSheet()->SetCellValue("N$i", 'CÓDIGO GLOSA');
                    $objPHPExcel->getActiveSheet()->SetCellValue("O$i", 'DESCRIPCIÓN GLOSA');
                    $objPHPExcel->getActiveSheet()->SetCellValue("P$i", 'ESTADO');
                    $objPHPExcel->getActiveSheet()->SetCellValue("Q$i", 'VALOR GLOSADO');
                    $objPHPExcel->getActiveSheet()->SetCellValue("R$i", 'VALOR LEVANTADO');
                    $objPHPExcel->getActiveSheet()->SetCellValue("S$i", 'VALOR ACEPTADO');
                    $objPHPExcel->getActiveSheet()->SetCellValue("T$i", 'VALOR X CONCILIAR');
                    $objPHPExcel->getActiveSheet()->SetCellValue("U$i", 'CODIGO DE RESPUESTA');
                    $objPHPExcel->getActiveSheet()->SetCellValue("V$i", 'DESCRIPCIÓN DE RESPUESTA');
                    
                    $EncabezadoFacturas=0;
                }
                    $i++;
                    $objPHPExcel->getActiveSheet()->SetCellValue("A$i", $DatosRespuesta["factura"]);
                    $objPHPExcel->getActiveSheet()->SetCellValue("B$i", $DatosRespuesta["fecha_factura"]);
                    $objPHPExcel->getActiveSheet()->SetCellValue("C$i", $DatosRespuesta["tipo_identificacion"]);
                    $objPHPExcel->getActiveSheet()->SetCellValue("D$i", $DatosRespuesta["identificacion"]);
                    $objPHPExcel->getActiveSheet()->SetCellValue("E$i", $DatosRespuesta["edad_usuario"]);
                    $UnidadEdad="";
                    if($DatosRespuesta["unidad_medida_edad"]==1){
                        $UnidadEdad="AÑOS";
                    }
                    if($DatosRespuesta["unidad_medida_edad"]==2){
                        $UnidadEdad="MESES";
                    }
                    if($DatosRespuesta["unidad_medida_edad"]==3){
                        $UnidadEdad="DÍAS";
                    }
                    $objPHPExcel->getActiveSheet()->SetCellValue("F$i", $UnidadEdad);
                    $objPHPExcel->getActiveSheet()->SetCellValue("G$i", $DatosRespuesta["sexo_usuario"]);
                    $objPHPExcel->getActiveSheet()->SetCellValue("H$i", $DatosRespuesta["valor_factura"]);
                    
                    //$i++;
                
                //$i++;
                $objPHPExcel->getActiveSheet()->SetCellValue("I$i", $DatosRespuesta["cuenta"]);
                $objPHPExcel->getActiveSheet()->SetCellValue("J$i", $DatosRespuesta["numero_radicado"]);
                //$objPHPExcel->getActiveSheet()->SetCellValue("K$i", $DatosRespuesta["factura"]);
                $objPHPExcel->getActiveSheet()->SetCellValue("K$i", $DatosRespuesta["cod_actividad"]);
                $objPHPExcel->getActiveSheet()->SetCellValue("L$i", $DatosRespuesta["valor_total_actividad"]);
                $objPHPExcel->getActiveSheet()->SetCellValue("M$i", $DatosRespuesta["fecha_respuesta"]);
                $objPHPExcel->getActiveSheet()->SetCellValue("N$i", $DatosRespuesta["cod_glosa_inicial"]);
                $objPHPExcel->getActiveSheet()->SetCellValue("O$i", utf8_encode($DatosRespuesta["descripcion_glosa_inicial"]));
                $objPHPExcel->getActiveSheet()->SetCellValue("P$i", utf8_encode($DatosRespuesta["descripcion_estado"]));
                $objPHPExcel->getActiveSheet()->SetCellValue("Q$i", $DatosRespuesta["valor_glosado_eps"]);
                $objPHPExcel->getActiveSheet()->SetCellValue("R$i", $DatosRespuesta["valor_levantado_eps"]);
                $objPHPExcel->getActiveSheet()->SetCellValue("S$i", $DatosRespuesta["valor_aceptado_ips"]);
                $objPHPExcel->getActiveSheet()->SetCellValue("T$i", $DatosRespuesta["valor_x_conciliar"]);
                $objPHPExcel->getActiveSheet()->SetCellValue("U$i", $DatosRespuesta["cod_glosa_respuesta"]);
                $objPHPExcel->getActiveSheet()->SetCellValue("V$i", utf8_encode($DatosRespuesta["descripcion_glosa_respuesta"]));
               
                
            }
            $this->update("salud_control_generacion_respuestas_excel", "Generada", 1, " WHERE num_factura='$idFactura'");
        
	
	//Guardamos los cambios
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save($NombreArchivo);
	
    }
    /**
     * Prepara y copia los soportes de las respuestas
     */
    public function PrepareSoportes() {
        $sql="SELECT re.idEPS,num_factura,nombre_completo,direccion,telefonos,email FROM salud_control_generacion_respuestas_excel re "
                . "INNER JOIN salud_eps se ON cod_pagador_min=idEPS WHERE re.Soportes=0";
        $consulta= $this->Query($sql);
        while ($DatosFacturas=$this->FetchAssoc($consulta)){
            $idFactura=$DatosFacturas["num_factura"];
            $sql="SELECT Soporte,factura,cod_actividad,cod_glosa_inicial,cod_glosa_respuesta "
                    . "FROM vista_salud_respuestas "
                    . "WHERE factura='$idFactura' AND (cod_estado=2 or cod_estado=4) AND Soporte<>''";
            $Datos= $this->Query($sql);
            while($DatosSoportes= $this->FetchAssoc($Datos)){
                $CodActividad=$DatosSoportes["cod_actividad"];
                $CodGlosaInicial=$DatosSoportes["cod_glosa_inicial"];
                $CodGlosaRespuesta=$DatosSoportes["cod_glosa_respuesta"];
                $SoporteArchivo="../../".$DatosSoportes["Soporte"];
                $info = new SplFileInfo($SoporteArchivo);
                $Extension=($info->getExtension());
                $SoporteRespuesta="../ArchivosTemporales/Reportes/Soportes/".$idFactura."_".$CodActividad."_".$CodGlosaInicial."_".$CodGlosaRespuesta.".$Extension";
                $SoporteRespuesta=str_replace(' ','_',$SoporteRespuesta); 
                //print($SoporteArchivo);
                if(file_exists($SoporteArchivo)){
                    copy($SoporteArchivo, $SoporteRespuesta);
                }
                
            }
        }
    }
    /**
     * Agrega Toda una carpeta a un .zip
     * @param type $dir
     * @param type $zip
     */
    function agregar_zip($dir, $zip) {
        //verificamos si $dir es un directorio
        if (is_dir($dir)) {
          //abrimos el directorio y lo asignamos a $da
          if ($da = opendir($dir)) {
            //leemos del directorio hasta que termine
            while (($archivo = readdir($da)) !== false) {
              /*Si es un directorio imprimimos la ruta
               * y llamamos recursivamente esta función
               * para que verifique dentro del nuevo directorio
               * por mas directorios o archivos
               */
              if (is_dir($dir . $archivo) && $archivo != "." && $archivo != "..") {
                //echo "<strong>Creando directorio: $dir$archivo</strong><br/>";
                agregar_zip($dir . $archivo . "/", $zip);

                /*si encuentra un archivo imprimimos la ruta donde se encuentra
                 * y agregamos el archivo al zip junto con su ruta 
                 */
              } elseif (is_file($dir . $archivo) && $archivo != "." && $archivo != "..") {
                //echo "Agregando archivo: $dir$archivo <br/>";
                $zip->addFile($dir . $archivo, "Soportes/".$archivo);
              }
            }
            //cerramos el directorio abierto en el momento
            closedir($da);
          }
        }
    }
    /**
     * Comprime las respuestas y archivos que se generaron
     * @param type $NombreArchivo
     */
    public function ComprimaRespuesta($NombreArchivo,$Soportes) {
        //creamos una instancia de ZipArchive
        $zip = new ZipArchive();

        /*directorio a comprimir
         * la barra inclinada al final es importante
         * la ruta debe ser relativa no absoluta
         */
        $dir = '../ArchivosTemporales/Reportes/Soportes/';

        //ruta donde guardar los archivos zip, ya debe existir
        $rutaFinal = "../ArchivosTemporales/Reportes/";

        if(!file_exists($rutaFinal)){
          mkdir($rutaFinal);
        }

        $archivoZip = $NombreArchivo;
        $RespuestaExcel=$rutaFinal."Respuestas.xlsx";
        if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
            if($Soportes==1){
                $this->agregar_zip($dir, $zip);
            }
          
            $zip->addFile($RespuestaExcel, "Respuestas.xlsx");
            $zip->close();

          //Muevo el archivo a una ruta
          //donde no se mezcle los zip con los demas archivos
          rename($archivoZip, "$rutaFinal/$archivoZip");

          //Hasta aqui el archivo zip ya esta creado
          //Verifico si el archivo ha sido creado
          if (file_exists($rutaFinal. "/" . $archivoZip)) {
            return("OK");
          } else {
            return("Error");
          }
        }
    }
   //Fin Clases
}
    