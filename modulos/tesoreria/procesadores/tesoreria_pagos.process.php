<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];

include_once("../clases/tesoreria.class.php");
include_once("../clases/SaludRips.class.php");

if( !empty($_REQUEST["Accion"]) ){
    
    $obCon=new Tesoreria($idUser);
    $obRips=new Rips($idUser);
    switch ($_REQUEST["Accion"]) {
        
        case 1: //Insertar un pago
            
            $Fecha=$obCon->normalizar($_REQUEST["Fecha"]);
            $CmbEps=$obCon->normalizar($_REQUEST["CmbEps"]);
            $CmbBanco=$obCon->normalizar($_REQUEST["CmbBanco"]);
            $NumeroTransaccion=$obCon->normalizar($_REQUEST["NumeroTransaccion"]);
            $CmbTipoPago=$obCon->normalizar($_REQUEST["CmbTipoPago"]);
            $ValorTransaccion=$obCon->normalizar($_REQUEST["ValorTransaccion"]);
            $Observaciones=$obCon->normalizar($_REQUEST["Observaciones"]);
            
            if(!empty($_FILES['UpSoporte']['name'])){
                
                $info = new SplFileInfo($_FILES['UpSoporte']['name']);
                $Extension=($info->getExtension());  
                
                $carpeta="../../../SoportesSalud/SoportesPagosTesoreria/";
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                opendir($carpeta);
                $Name=str_replace(' ','_',$_FILES['UpSoporte']['name']);  
                $destino=$carpeta.$Name;
                move_uploaded_file($_FILES['UpSoporte']['tmp_name'],$destino);

                
            }else{
                exit("E1;No se envió ningún archivo soporte");
                
            }
            if($Fecha==''){
                exit("E1;Debe escribir una Fecha;Fecha");
            }
            if($CmbEps==''){
                exit("E1;Debe Seleccionar una EPS;CmbEps");
            }
            if($CmbBanco==''){
                exit("E1;Debe escribir un Banco;CmbBanco");
            }
            if($NumeroTransaccion==''){
                exit("E1;Debe escribir un Número de Transacción;NumeroTransaccion");
            }
            if($CmbTipoPago==''){
                exit("E1;Seleccione un tipo de pago;CmbTipoPago");
            }
            if($Observaciones==''){
                exit("E1;Debe escribir las observaciones;Observaciones");
            }
            if(!is_numeric($ValorTransaccion) or $ValorTransaccion<=0){
                exit("E1;El campo cantidad debe ser un numero mayor a Cero, no sea mk;Cantidad");
            }
            
            
            $obCon->IngresarPagoTesoreria($Fecha,$CmbEps,$CmbBanco,$NumeroTransaccion,$CmbTipoPago,$ValorTransaccion,$Observaciones,$destino,$idUser);
            print("OK;Pago ingresado correctamente");
        break;//Fin caso 1
        
        case 2://Recibe el archivo de pagos
            
            $obRips->VaciarTabla("salud_pagos_temporal"); //Vacío la tabla de subida temporal
            $obCon->VaciarTabla("salud_pagos_contributivo_temp");
            $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
            $TipoGiro=$obRips->normalizar($_REQUEST["CmbTipoGiro"]);
            $FechaGiro=$obRips->normalizar($_REQUEST["TxtFechaGira"]);
            $FechaCargue=date("Y-m-d H:i:s");
            $destino="";
            
            if(!empty($_FILES['UpPago']['name'])){
                $Atras="../";
                $carpeta=$Atras."ArchivosTemporales/";
                opendir($carpeta);
                $NombreArchivo=str_replace(' ','_',$_FILES['UpPago']['name']);  
                $NombreArchivo=str_replace('%','_',$_FILES['UpPago']['name']); 
                move_uploaded_file($_FILES['UpPago']['tmp_name'],$carpeta.$NombreArchivo);
                
                $handle = fopen($carpeta.$NombreArchivo, "r");
                //print($carpeta.$NombreArchivo);
                if($Separador==1){
                    $SeparadorT=";"; 
                 }else{
                    $SeparadorT=",";  
                 }
                
                $i=0;
                while (($data = fgetcsv($handle, 1000, $SeparadorT,'"', "#")) !== FALSE) {
                     //print_r($data);
                    $i++;
                    //$data[$i]=str_ireplace('"','',$data[$i]); 
                    //$data[$i]=str_replace('"','',$data[$i]); 
                    
                    if($i==2){
                        if($TipoGiro==1){
                            if(!isset($data[10])){
                                exit("El archivo no es válido");
                            }
                            $idGiro= uniqid("_");
                            $idGiro=str_replace(" ","",$idGiro);
                            $Giro=$idGiro;
                        }
                        if($TipoGiro==3 or $TipoGiro==2){
                            if(!isset($data[1])){
                                exit("E1;El archivo no es válido");
                            }
                          
                            $idGiro= uniqid("_");
                            $idGiro=str_replace(" ","",$idGiro);
                            $Giro=$idGiro;
                        }
                       // print($Giro);
                        
                    }
                }
                fclose($handle);
                
                $NombreAR="AR".$Giro.".txt";
                $destinoAR="../../../VSalud/archivos/".$NombreAR;
                copy($carpeta.$NombreArchivo,$destinoAR);
                unlink($carpeta.$NombreArchivo);
                
               
                                
            }
            
            //Verificamos si el archivo ya fue subido
            
            $DatosUploads=$obRips->DevuelveValores("salud_upload_control", "nom_cargue", $NombreAR);
            
            if($DatosUploads["id_upload_control"]==''){
                
                if(!empty($_FILES['UpSoporte']['name'])){
                    $Name='';
                    $info = new SplFileInfo($_FILES['UpSoporte']['name']);
                    $Extension=($info->getExtension());
                    $Atras="../";
                    $carpeta="SoportesSalud/SoportesAR/";
                    opendir($Atras.$Atras.$Atras.$carpeta);
                    $Name=$NombreAR.".".$Extension;  
                    //$Name=str_replace('%','_',$_FILES['UpSoporte']['name']);  
                    $destino=$carpeta.$Name;
                    //print($destino);
                    move_uploaded_file($_FILES['UpSoporte']['tmp_name'],$Atras.$Atras.$Atras.$destino);
                }
                
                $obRips->VaciarTabla("salud_subir_rips_pago_control");
                $Datos["ArchivoActual"]=$NombreAR;
                $Datos["idUser"]=$idUser;
                $Datos["Separador"]=$Separador;
                $Datos["Destino"]=$destino;
                $Datos["FechaGiro"]=$FechaGiro;
                $Datos["FechaCargue"]=$FechaCargue;
                $Datos["TipoGiro"]=$TipoGiro;
                $sql=$obRips->getSQLInsert("salud_subir_rips_pago_control", $Datos);
                $obRips->Query($sql);
                
                print("OK");
                
            }else{
                $css->CrearNotificacionAzul("El archivo ya fue subido el $DatosUploads[fecha_cargue], por el usuario $DatosUploads[idUser]",16);
            }
            
            
        break;//Fin caso 2  
        
        case 3://Insertar RIPS de Pagos
            $TipoGiro=$obRips->normalizar($_REQUEST["CmbTipoGiro"]);
            $idPago=$obRips->normalizar($_REQUEST["idPago"]);
            //print("Tipo de Giro".$TipoGiro);
            
            $DatosArchivoActual=$obRips->DevuelveValores("salud_subir_rips_pago_control", "ID", 1);
            
            if($TipoGiro==1 ){
                
                $obRips->InsertarRipsPagosAdres($DatosArchivoActual["ArchivoActual"],$DatosArchivoActual["Separador"], $DatosArchivoActual["FechaCargue"], $idUser,$DatosArchivoActual["Destino"],$DatosArchivoActual["FechaGiro"],$DatosArchivoActual["TipoGiro"], "");
                $sql="SELECT SUM(ValorGiro) as TotalPagoAR FROM salud_pagos_temporal";
                $Totales=$obCon->FetchAssoc($obCon->Query($sql));
                $DatosPago=$obCon->DevuelveValores("salud_tesoreria", "ID", $idPago);

                if($Totales["TotalPagoAR"]>$DatosPago["valor_legalizar"]){
                    exit("E1;El valor en el AR (". number_format($Totales["TotalPagoAR"]).") Supera el Valor por Legalizar (".$DatosPago["valor_legalizar"].")");
                }
                
                $obRips->AnaliceInsercionFacturasPagadasAdres("");
            }
            if($TipoGiro==3 or $TipoGiro==2){
               
                $obRips->InsertarRipsPagosAdresContributivoTemporal($DatosArchivoActual["ArchivoActual"],$DatosArchivoActual["Separador"], $DatosArchivoActual["FechaCargue"], $idUser,$DatosArchivoActual["Destino"],$DatosArchivoActual["FechaGiro"],$DatosArchivoActual["TipoGiro"], "");
                                
                $sql="UPDATE salud_pagos_contributivo_temp pt INNER JOIN salud_archivo_facturacion_mov_generados mg ON mg.num_factura=pt.numero_factura 
                        SET pt.CodigoEps=mg.cod_enti_administradora, pt.FechaFactura=mg.fecha_factura, pt.FormaContratacion=mg.tipo_negociacion;";
                $obCon->Query($sql);
                $sql="SELECT SUM(ValorGiro) as TotalPagoAR FROM salud_pagos_contributivo_temp";
                $Totales=$obCon->FetchAssoc($obCon->Query($sql));
                $DatosPago=$obCon->DevuelveValores("salud_tesoreria", "ID", $idPago);
                
                if($Totales["TotalPagoAR"]>$DatosPago["valor_legalizar"]){
                    exit("El valor en el AR (". number_format($Totales["TotalPagoAR"]).") Supera el Valor por Legalizar (".$DatosPago["valor_legalizar"].")");
                }
                
                $sql="SELECT t1.* FROM salud_pagos_contributivo_temp t1  WHERE EXISTS (SELECT 1 FROM `salud_pagos_contributivo` as t2 WHERE t1.`FechaPago`=t2.`FechaPago` and t1.`numero_factura`=t2.`numero_factura` AND t1.`NitEPS`=t2.`NitEPS`) LIMIT 10;"; 
                $Consulta=$obCon->Query($sql);
                $error=0;
                while ($DatosTemporal=$obCon->FetchAssoc($Consulta)){
                    $error=1;
                    print("El pago de la Factura ".$DatosTemporal["numero_factura"].", por valor de ". number_format($DatosTemporal["ValorGiro"])." ya fue cargado<br>");
                }
                if($error==1){
                    exit();
                }
                $sql="INSERT INTO `salud_pagos_contributivo` SELECT * FROM `salud_pagos_contributivo_temp` as t1 
                        WHERE NOT EXISTS (SELECT 1 FROM `salud_pagos_contributivo` as t2 WHERE t1.`FechaPago`=t2.`FechaPago` and t1.`numero_factura`=t2.`numero_factura` AND t1.`NitEPS`=t2.`NitEPS`);"; 
                $obCon->Query($sql);
                
                $sql="INSERT INTO `salud_pagos_temporal` (`id_temp_rips_generados`, `Proceso`, `CodigoEPS`, `NombreEPS`, `FormaContratacion`, `Departamento`, `Municipio`, `FechaFactura`, `PrefijoFactura`, `NumeroFactura`, `ValorGiro`, `FechaPago`, `NumeroGiro`, `nom_cargue`, `fecha_cargue`, `idUser`, `Soporte`,`numero_factura`)
                SELECT '',Proceso,CodigoEps,NombreEPS,FormaContratacion,'','',FechaFactura,PrefijoFactura,NumeroFactura,ValorGiro,FechaPago,'','',fecha_cargue,idUser,Soporte,numero_factura FROM salud_pagos_contributivo WHERE Estado=0";
                $obCon->Query($sql);
                $obCon->update("salud_pagos_contributivo", "Estado", 1, "");
                $obRips->AjusteAutoIncrement("salud_pagos_contributivo", "ID", "");
                
                $TipoMovimiento="Contributivo";
                if($TipoGiro==2){
                    $TipoMovimiento="CuentaMaestra";
                }
                
                $obRips->AnaliceInsercionFacturasPagadasAdres("",$TipoMovimiento);
                //print("Subido");
            }
            print("OK;".$Totales["TotalPagoAR"]);
        break;//Fin caso 3    
        
        case 4:// encuentre facturas con diferencia
            
            $obRips->EncuentreFacturasPagadasConDiferencia("");
            print("OK");
            
        break;//Fin caso 4    
    
        case 5:// encuentre facturas pagadas
            
            $obRips->EncuentreFacturasPagadas("");            
            print("OK");
            
        break;//Fin caso 5
        
        case 6://Actualiza las observaciones de cartera tras subir un archivo de pagos
            $Observaciones=$obCon->normalizar($_REQUEST["Observaciones"]);
            $idPago=$obCon->normalizar($_REQUEST["idPago"]);
            $TotalPago=$obCon->normalizar($_REQUEST["ValorPagos"]);
            $DatosPago=$obCon->DevuelveValores("salud_tesoreria", "ID", $idPago);
            $TotalLegalizado=$DatosPago["valor_legalizado"]+$TotalPago;
            $TotalXLegalizar=$DatosPago["valor_transaccion"]-$TotalLegalizado;
            $obCon->ActualizaRegistro("salud_tesoreria", "observaciones_cartera", $Observaciones, "ID", $idPago);
            
            $obCon->ActualizaRegistro("salud_tesoreria", "valor_legalizado", $TotalLegalizado, "ID", $idPago,0);
            
            $obCon->ActualizaRegistro("salud_tesoreria", "valor_legalizar", $TotalXLegalizar, "ID", $idPago,0);
            if($TotalXLegalizar<=0){
                $obCon->ActualizaRegistro("salud_tesoreria", "legalizado", "SI", "ID", $idPago);
            }
            print("OK");
        break;//fin caso 6   
    
        case 7://Editar un pago
            $idPago=$obCon->normalizar($_REQUEST["idPago"]);
            $Fecha=$obCon->normalizar($_REQUEST["Fecha"]);
            $CmbEps=$obCon->normalizar($_REQUEST["CmbEps"]);
            $CmbBanco=$obCon->normalizar($_REQUEST["CmbBanco"]);
            $NumeroTransaccion=$obCon->normalizar($_REQUEST["NumeroTransaccion"]);
            $CmbTipoPago=$obCon->normalizar($_REQUEST["CmbTipoPago"]);
            $ValorTransaccion=$obCon->normalizar($_REQUEST["ValorTransaccion"]);
            $Observaciones=$obCon->normalizar($_REQUEST["Observaciones"]);
            
            if($Fecha==''){
                exit("E1;Debe escribir una Fecha;Fecha");
            }
            if($CmbEps==''){
                exit("E1;Debe Seleccionar una EPS;CmbEps");
            }
            if($CmbBanco==''){
                exit("E1;Debe escribir un Banco;CmbBanco");
            }
            if($NumeroTransaccion==''){
                exit("E1;Debe escribir un Número de Transacción;NumeroTransaccion");
            }
            if($CmbTipoPago==''){
                exit("E1;Seleccione un tipo de pago;CmbTipoPago");
            }
            if($Observaciones==''){
                exit("E1;Debe escribir las observaciones;Observaciones");
            }
            if(!is_numeric($ValorTransaccion) or $ValorTransaccion<=0){
                exit("E1;El campo cantidad debe ser un numero mayor a Cero, no sea mk;Cantidad");
            }
            $DatosPago= $obCon->DevuelveValores("salud_tesoreria", "ID", $idPago);
            
            $ValorXLegalizar=$ValorTransaccion-$DatosPago["valor_legalizado"];
            if($ValorXLegalizar<0){
                exit("E1;El Valor de la transaccion digitado supera al valor por legalizar, la operacion no puede ser realizada;ValorTransaccion");
            }
            
            $obCon->EditarPagoTesoreria($idPago,$Fecha,$CmbEps,$CmbBanco,$NumeroTransaccion,$CmbTipoPago,$ValorTransaccion,$ValorXLegalizar,$Observaciones,$idUser);
            print("OK;Pago editado correctamente");
            
        break;//Fin caso 7    
        
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>