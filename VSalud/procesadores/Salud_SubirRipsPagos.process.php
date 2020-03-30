<?php

/* 
 * Este archivo procesa la carga de los rips de pagos
 */
if(isset($_REQUEST["BtnEnviar"])){
    $obRips = new Rips($idUser);
    $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
    $TipoGiro=$obRips->normalizar($_REQUEST["CmbTipoGiro"]);
    $FechaGiro=$obRips->normalizar($_REQUEST["TxtFechaGira"]);
    $FechaCargue=date("Y-m-d H:i:s");
    $destino="";
    if(!empty($_FILES['UpSoporte']['name'])){
        //echo "<script>alert ('entra foto')</script>";
        $Atras="../";
        $carpeta="SoportesSalud/SoportesAR/";
        opendir($Atras.$carpeta);
        $Name=str_replace(' ','_',$_FILES['UpSoporte']['name']);  
        $destino=$carpeta.$Name;
        move_uploaded_file($_FILES['UpSoporte']['tmp_name'],$Atras.$destino);
    }
    if(!empty($_FILES['UpPago']['type'])){
        //if($_FILES['UpZipPagos']['type']=='application/x-zip-compressed'){
            
            
            $carpeta="ArchivosTemporales/";
            opendir($carpeta);
            $NombreArchivo=str_replace(' ','_',$_FILES['UpPago']['name']);  
            move_uploaded_file($_FILES['UpPago']['tmp_name'],$carpeta.$NombreArchivo);
            $handle = fopen("ArchivosTemporales/".$NombreArchivo, "r");
            if($Separador==1){
                $SeparadorT=";"; 
             }else{
                $SeparadorT=",";  
             }
            
            $i=0;
            while (($data = fgetcsv($handle, 1000, $SeparadorT)) !== FALSE) {
                $i++;
                if($i==2){
                    $Giro=str_replace(".","",$data[10]);
                    $Giro=str_replace(",00","",$Giro);
                }
            }
            
            
            fclose($handle);
            $NombreAR="AR".$Giro.".txt";
            $destinoAR="archivos/".$NombreAR;
            copy("ArchivosTemporales/".$NombreArchivo,$destinoAR);
            unlink("ArchivosTemporales/".$NombreArchivo);
            //$obRips->VerificarZip($_FILES['UpZipPagos']['tmp_name'],$idUser, "");
        //}else{
          //  $css->CrearNotificacionRoja("Debe cargar un archivo .zip",16);
          //  goto salir;
        //}
        //$consulta= $obRips->ConsultarTabla("salud_upload_control", " WHERE Analizado='0'");
            $obRips->VaciarTabla("salud_pagos_temporal"); //Vacío la tabla de subida temporal

            //while($DatosArchivos= $obRips->FetchArray($consulta)){
              //  $NombreArchivo=$DatosArchivos["nom_cargue"]; 
                //$Prefijo=substr($NombreArchivo, 0, 2); 
                //Si hay Archivos de Recaudo
                //if($Prefijo=="AR"){
            $DatosUploads=$obRips->DevuelveValores("salud_upload_control", "nom_cargue", $NombreAR);
            if($DatosUploads["id_upload_control"]==''){
                if($TipoGiro==1 or $TipoGiro==2){
                    $obRips->InsertarRipsPagosAdres($NombreAR,$Separador, $FechaCargue, $idUser,$destino,$FechaGiro,$TipoGiro, "");
                    $NumRegistros=$obRips->CalculeRegistros("archivos/".$NombreAR,$Separador); // se calculan cuantos registros tiene el archivo
                    $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros del archivo $NombreAR cargados correctamente",16);
                }
                
                if($TipoGiro==3){
                    $obRips->InsertarRipsPagosAdresContributivoTemporal($NombreAR, $Separador, $FechaCargue, $idUser, $destino, $FechaGiro, $TipoGiro, "");
                    exit();
                }
            }else{
                $css->CrearNotificacionAzul("El archivo ya fue subido el $DatosUploads[fecha_cargue], por el usuario $DatosUploads[idUser]",16);
            }
            $css->CrearNotificacionNaranja("Tabla de Facturas Pagadas Analizada",16);
            $obRips->EncuentreFacturasPagadasConDiferencia("");
            $css->CrearNotificacionVerde("Facturas pagadas con diferencias verificadas",16);
            $obRips->EncuentreFacturasPagadas("");
            $css->CrearNotificacionAzul("Facturas pagadas con igual valor verificadas",16);
                //}
            //}
            $obRips->AnaliceInsercionFacturasPagadasAdres("");
    
        
             
             
    }
}
salir:
?>