<?php
if(file_exists("../../../modelo/php_conexion.php")){
    include_once("../../../modelo/php_conexion.php");
}
/* 
 * Clase donde se realizaran procesos de la cartera IPS
 * Julian Alvaran
 * Techno Soluciones SAS
 * 2018-09-26
 */
        
class ActualizacionFacturas extends conexion{
    
    
    
    public function ActualizarFactura($NumeroFacturaAnterior,$NumeroFacturaNueva,$Observaciones,$idUser) {
        
        
        $Fecha=date("Y-m-d H:i:s");
        $Datos["FacturaAnterior"]=$NumeroFacturaAnterior;        
        $Datos["FacturaNueva"]=$NumeroFacturaNueva;
        $Datos["Observaciones"]=$Observaciones;
        $Datos["idUser"]=$idUser;
        $Datos["FechaRegistro"]=$Fecha;
        
        $sql=$this->getSQLInsert("registro_actualizacion_facturas", $Datos);
        $this->Query($sql);
        
        $this->ActualizaRegistro("salud_archivo_consultas", "num_factura", $NumeroFacturaNueva, "num_factura", $NumeroFacturaAnterior);
        $this->ActualizaRegistro("salud_archivo_facturacion_mov_generados", "num_factura", $NumeroFacturaNueva, "num_factura", $NumeroFacturaAnterior);
        $this->ActualizaRegistro("salud_archivo_hospitalizaciones", "num_factura", $NumeroFacturaNueva, "num_factura", $NumeroFacturaAnterior);
        $this->ActualizaRegistro("salud_archivo_medicamentos", "num_factura", $NumeroFacturaNueva, "num_factura", $NumeroFacturaAnterior);
        $this->ActualizaRegistro("salud_archivo_nacidos", "num_factura", $NumeroFacturaNueva, "num_factura", $NumeroFacturaAnterior);
        $this->ActualizaRegistro("salud_archivo_otros_servicios", "num_factura", $NumeroFacturaNueva, "num_factura", $NumeroFacturaAnterior);
        $this->ActualizaRegistro("salud_archivo_procedimientos", "num_factura", $NumeroFacturaNueva, "num_factura", $NumeroFacturaAnterior);
        $this->ActualizaRegistro("salud_archivo_urgencias", "num_factura", $NumeroFacturaNueva, "num_factura", $NumeroFacturaAnterior);
        
        
        
    }
    
    function LeerCargarTemporal($keyArchivo,$RutaArchivo,$Extension,$idUser) {
        
        require_once('../../../librerias/Excel/PHPExcel.php');
        require_once('../../../librerias/Excel/PHPExcel/Reader/Excel2007.php');
        //$DatosIPS=$this->DevuelveValores("ips", "NIT", $idIPS);
        //$db=$DatosIPS["DataBase"];
        
        $FechaActual=date("Y-m-d H:i:s");
        
        if($Extension=="xlsx"){
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        }else if($Extension=="xls"){
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
        }else{
            exit("Solo se permiten archivos con extension xls o xlsx");
        }
        
        //$objReader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load($RutaArchivo);   
        $hojas=$objPHPExcel->getSheetCount();
                 
        $objFecha = new PHPExcel_Shared_Date();    
        
        date_default_timezone_set('UTC'); //establecemos la hora local
        $Proceso="";
        $DescripcionProceso="";
        $Estado="";
        $Cuenta="";
        $Banco="";
        $Cols=['A','B','C'];
        for ($h=0;$h<$hojas;$h++){
            $objPHPExcel->setActiveSheetIndex($h);
            $columnas = $objPHPExcel->setActiveSheetIndex($h)->getHighestColumn();
            $filas = $objPHPExcel->setActiveSheetIndex($h)->getHighestRow();
            if($filas>100){
                exit("E1;El archivo no puede tener mas de 100 facturas a actualizar");
            }
            if($columnas<>'C'){
                exit('E1;<h3>No se recibió el archivo de <strong>Actualización de facturas para IPS</strong></h3>');
            }
            
            for ($i=2;$i<=$filas;$i++){
                $FilaA=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
                
                if($FilaA==''){

                    continue; 

                }
                

                    $c=0;
                    $_DATOS_EXCEL[$i]['FacturaAnterior']= $objPHPExcel->getActiveSheet()->getCell($Cols[$c].$i)->getCalculatedValue();
                    $_DATOS_EXCEL[$i]['FacturaNueva'] = $objPHPExcel->getActiveSheet()->getCell($Cols[++$c].$i)->getCalculatedValue();
                    $_DATOS_EXCEL[$i]['Observaciones'] = $objPHPExcel->getActiveSheet()->getCell($Cols[++$c].$i)->getCalculatedValue();
                    
                    $_DATOS_EXCEL[$i]['idUser'] = $idUser;                    
                    $_DATOS_EXCEL[$i]['FechaRegistro'] = $FechaActual;
                    

            } 
        
        }
        
        $sql= "INSERT INTO `temporal_actualizacion_facturas` ( `FacturaAnterior`, `FacturaNueva`,`Observaciones`, `idUser`, `FechaRegistro`) VALUES ";
        $i=0;    
        foreach($_DATOS_EXCEL as $campo => $valor){
            $i++;
            $sql.="('";
            foreach ($valor as $campo2 => $valor2){
                $campo2 == "FechaRegistro" ? $sql.= $valor2."')," : $sql.= $valor2."','";
            }
            
            if($i==1000){
                
                $sql=substr($sql, 0, -1);
                //print($sql);
                $this->Query($sql);
                $sql= "INSERT INTO `temporal_actualizacion_facturas` ( `FacturaAnterior`, `FacturaNueva`,`Observaciones`, `idUser`, `FechaRegistro`) VALUES ";
                $i=0;
            }    
            
        }   
        $sql=substr($sql, 0, -1);
        $this->Query($sql);
        unset($objPHPExcel);
        unset($_DATOS_EXCEL);
        unset($sql);
    }
    
    public function MarcarConciliacionXEPS($db,$NumeroFactura,$idUser,$TipoConciliacion) {
        if($TipoConciliacion==1){
            $Tabla1="carteraeps";
            $Tabla2="carteracargadaips";
        }else{
            $Tabla2="carteraeps";
            $Tabla1="carteracargadaips";
        }
        
        $sql="UPDATE $db.$Tabla1 SET ConciliadoXEPS=1 WHERE NumeroFactura='$NumeroFactura' ";
        $this->Query($sql);
        
        $sql="UPDATE $db.$Tabla2 SET ConciliadoXEPS=0 WHERE NumeroFactura='$NumeroFactura' ";
        $this->Query($sql);
    }
    
    public function MarcarConciliacionXIPS($db,$NumeroFactura,$idUser,$TipoConciliacion) {
        if($TipoConciliacion==1){
            $Tabla1="carteraeps";
            $Tabla2="carteracargadaips";
        }else{
            $Tabla2="carteraeps";
            $Tabla1="carteracargadaips";
        }
        
        $sql="UPDATE $db.$Tabla1 SET ConciliadoXIPS=1 WHERE NumeroFactura='$NumeroFactura' ";
        $this->Query($sql);
        
        $sql="UPDATE $db.$Tabla2 SET ConciliadoXIPS=0 WHERE NumeroFactura='$NumeroFactura' ";
        $this->Query($sql);
                
    }
    
    public function RegistreConciliacionUsuario($db,$idUser,$NumeroFactura,$TipoConciliacion) {
        $sql="SELECT NumeroContrato FROM $db.carteraeps WHERE NumeroFactura='$NumeroFactura'";
        $Consulta=$this->Query($sql);
        $DatosContrato= $this->FetchAssoc($Consulta);
        $Contrato=$DatosContrato["NumeroContrato"];
        $key="$Contrato $NumeroFactura";
        $FechaRegistro=date("Y-m-d H:i:s");
        $Datos["ID"]=$key;
        $Datos["NumeroFactura"]=$NumeroFactura;
        $Datos["NumeroContrato"]=$Contrato;
        $Datos["TipoConciliacion"]=$TipoConciliacion;
        $Datos["idUser"]=$idUser;
        $Datos["FechaRegistro"]=$FechaRegistro;
        
        $sql=$this->getSQLReeplace("$db.registro_conciliaciones_ips_eps", $Datos);
        $this->Query($sql);
        
    }
    
    
    //Fin Clases
}
