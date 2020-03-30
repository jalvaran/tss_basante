<?php

/* 
 * Este archivo procesa la carga de los rips de pagos
 */
if(isset($_REQUEST["BtnEnviar"])){
    $obRips = new Rips($idUser);
    $Separador=2;
    
    $FechaCargue=date("Y-m-d H:i:s");
    
    if(!empty($_FILES['UpPago']['type'])){
            
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
            
            $DatosUploads=$obRips->DevuelveValores("salud_upload_control", "nom_cargue", $NombreArchivo);
            if($DatosUploads["id_upload_control"]==''){
                $obRips->VaciarTabla("salud_circular030_inicial"); //Vacío la tabla de subida temporal
                $obRips->SubirCircular030Inicial($NombreArchivo, $Separador, $FechaCargue, $idUser, "");
                $NumRegistros=$obRips->CalculeRegistros("ArchivosTemporales/".$NombreArchivo,$Separador); // se calculan cuantos registros tiene el archivo
                $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros del archivo $NombreArchivo cargados correctamente",16);
                
            }else{
                $css->CrearNotificacionAzul("El archivo ya fue subido el $DatosUploads[fecha_cargue], por el usuario $DatosUploads[idUser]",16);
            }
            
            $obRips->InserteRegistrosAFDesde030Inicial("");
            $css->CrearNotificacionAzul("Registros AF Insertados a partir de la Circular 030 Inicial",16);
            $obRips->InserteARDesde030Inicial("");
            $css->CrearNotificacionVerde("Registros AR Insertados a partir de la Circular 030 Inicial",16);
            $obRips->RegistroDeGlosasDesde030Inicial("");
            $css->CrearNotificacionAzul("Se registraron las glosas a partir de la Circular 030 Inicial",16);
            //$obRips->EncuentreFacturasPagadas("");
            //$css->CrearNotificacionAzul("Facturas pagadas con igual valor verificadas",16);
                //}
            //}
            //$obRips->AnaliceInsercionFacturasPagadasAdres("");
    
        
             
             
    }
}

?>