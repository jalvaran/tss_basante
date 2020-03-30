<?php

/* 
 * Este procesador sube los archivos RIPS generados por la IPS
 */
$obRips = new Rips($idUser);
if(isset($_REQUEST["CmbSeparador"])){
    $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
}

if(isset($_REQUEST["CmbTipoNegociacion"])){
    $TipoNegociacion=$obRips->normalizar($_REQUEST["CmbTipoNegociacion"]);
}


$FechaCargue=date("Y-m-d H:i:s");
//Archivo de consultas
/*
if(!empty($_FILES['UpAC']['name'])){ 
    $NombreArchivo=$obRips->normalizar($_FILES['UpAC']['name']);
    $DatosUploads=$obRips->DevuelveValores("salud_upload_control", "nom_cargue", $NombreArchivo);
    if($DatosUploads["nom_cargue"]==$NombreArchivo){
        $css->CrearNotificacionRoja("El archivo $NombreArchivo ya fue cargado el $DatosUploads[fecha_cargue] por el Usuario: $DatosUploads[idUser]",16);
    } else {
        $FileName='UpAC';
        $NumRegistros=$obRips->CalculeRegistros($FileName,$Separador); // se calculan cuantos registros tiene el archivo

        $obRips->VaciarTabla("salud_archivo_consultas_temp"); //Vacío la tabla de subida temporal
        $obRips->InsertarRipsConsultas($TipoNegociacion,$Separador,$FechaCargue, $idUser, "");
        $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros Consultas AC cargados correctamente",16);
    }
}

//Archivo de Hospitalizacion

if(!empty($_FILES['UpAH']['name'])){ 
    $NombreArchivo=$obRips->normalizar($_FILES['UpAH']['name']);
    $DatosUploads=$obRips->DevuelveValores("salud_upload_control", "nom_cargue", $NombreArchivo);
    if($DatosUploads["nom_cargue"]==$NombreArchivo){
        $css->CrearNotificacionRoja("El archivo $NombreArchivo ya fue cargado el $DatosUploads[fecha_cargue] por el Usuario: $DatosUploads[idUser]",16);
    } else {
        $FileName='UpAH';
        $NumRegistros=$obRips->CalculeRegistros($FileName,$Separador); // se calculan cuantos registros tiene el archivo

        $obRips->VaciarTabla("salud_archivo_hospitalizaciones_temp"); //Vacío la tabla de subida temporal
        $obRips->InsertarRipsHospitalizaciones($TipoNegociacion,$Separador,$FechaCargue, $idUser, "");
        $css->CrearNotificacionAzul(number_format($NumRegistros)." Registros Hospitalizaciones AH cargados correctamente",16);
    }
}

//Archivo de Medicamentos

if(!empty($_FILES['UpAM']['name'])){ 
    $NombreArchivo=$obRips->normalizar($_FILES['UpAM']['name']);
    $DatosUploads=$obRips->DevuelveValores("salud_upload_control", "nom_cargue", $NombreArchivo);
    if($DatosUploads["nom_cargue"]==$NombreArchivo){
        $css->CrearNotificacionRoja("El archivo $NombreArchivo ya fue cargado el $DatosUploads[fecha_cargue] por el Usuario: $DatosUploads[idUser]",16);
    } else {
        $FileName='UpAM';
        $NumRegistros=$obRips->CalculeRegistros($FileName,$Separador); // se calculan cuantos registros tiene el archivo

        $obRips->VaciarTabla("salud_archivo_medicamentos_temp"); //Vacío la tabla de subida temporal
        $obRips->InsertarRipsMedicamentos($TipoNegociacion,$Separador,$FechaCargue, $idUser, "");
        $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros Medicamentos AM cargados correctamente",16);
    }
}

//Archivo de Procedimientos

if(!empty($_FILES['UpAP']['name'])){ 
    $NombreArchivo=$obRips->normalizar($_FILES['UpAP']['name']);
    $DatosUploads=$obRips->DevuelveValores("salud_upload_control", "nom_cargue", $NombreArchivo);
    if($DatosUploads["nom_cargue"]==$NombreArchivo){
        $css->CrearNotificacionRoja("El archivo $NombreArchivo ya fue cargado el $DatosUploads[fecha_cargue] por el Usuario: $DatosUploads[idUser]",16);
    } else {
    
        $FileName='UpAP';
        $NumRegistros=$obRips->CalculeRegistros($FileName,$Separador); // se calculan cuantos registros tiene el archivo

        $obRips->VaciarTabla("salud_archivo_procedimientos_temp"); //Vacío la tabla de subida temporal
        $obRips->InsertarRipsProcedimientos($TipoNegociacion,$Separador,$FechaCargue, $idUser, "");
        $css->CrearNotificacionAzul(number_format($NumRegistros)." Registros Procedimientos AP cargados correctamente",16);
    }
}

//Archivo de Otros Servicios

if(!empty($_FILES['UpAT']['name'])){ 
   
    $NombreArchivo=$obRips->normalizar($_FILES['UpAT']['name']);
    $DatosUploads=$obRips->DevuelveValores("salud_upload_control", "nom_cargue", $NombreArchivo);
    if($DatosUploads["nom_cargue"]==$NombreArchivo){
        $css->CrearNotificacionRoja("El archivo $NombreArchivo ya fue cargado el $DatosUploads[fecha_cargue] por el Usuario: $DatosUploads[idUser]",16);
    } else {
        $FileName='UpAT';
        $NumRegistros=$obRips->CalculeRegistros($FileName,$Separador); // se calculan cuantos registros tiene el archivo

        $obRips->VaciarTabla("salud_archivo_otros_servicios_temp"); //Vacío la tabla de subida temporal
        $obRips->InsertarRipsOtrosServicios($TipoNegociacion,$Separador,$FechaCargue, $idUser, "");
        $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros Otros Servicios AT cargados correctamente",16);
    }
}

//Archivo de Usuarios

if(!empty($_FILES['UpUS']['name'])){ 
   
    $NombreArchivo=$obRips->normalizar($_FILES['UpUS']['name']);
    $DatosUploads=$obRips->DevuelveValores("salud_upload_control", "nom_cargue", $NombreArchivo);
    if($DatosUploads["nom_cargue"]==$NombreArchivo){
        $css->CrearNotificacionRoja("El archivo $NombreArchivo ya fue cargado el $DatosUploads[fecha_cargue] por el Usuario: $DatosUploads[idUser]",16);
    } else {
        $FileName='UpUS';
        $NumRegistros=$obRips->CalculeRegistros($FileName,$Separador); // se calculan cuantos registros tiene el archivo

        $obRips->VaciarTabla("salud_archivo_usuarios_temp"); //Vacío la tabla de subida temporal
        $obRips->InsertarRipsUsuarios($TipoNegociacion,$Separador,$FechaCargue, $idUser, "");
        $css->CrearNotificacionAzul(number_format($NumRegistros)." Registros de Usuarios US cargados correctamente",16);
    }
}

//Archivo de Usuarios

if(!empty($_FILES['UpAF']['name'])){ 
    $NombreArchivo=$obRips->normalizar($_FILES['UpAF']['name']);
    $DatosUploads=$obRips->DevuelveValores("salud_upload_control", "nom_cargue", $NombreArchivo);
    if($DatosUploads["nom_cargue"]==$NombreArchivo){
        $css->CrearNotificacionRoja("El archivo $NombreArchivo ya fue cargado el $DatosUploads[fecha_cargue] por el Usuario: $DatosUploads[idUser]",16);
    } else {
        $FileName='UpAF';
        $NumRegistros=$obRips->CalculeRegistros($FileName,$Separador); // se calculan cuantos registros tiene el archivo

        $obRips->VaciarTabla("salud_rips_facturas_generadas_temp"); //Vacío la tabla de subida temporal
        $Mensaje=$obRips->InsertarRipsFacturacionGenerada($TipoNegociacion,$Separador,$FechaCargue, $idUser, "");

        $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros de Facturas Generadas AF cargados correctamente",16);

    }
    
    
}


*/
//Archivo ZIP con los archivos cargados

if(isset($_REQUEST["BtnSubirZip"])){ 
    
    if(!empty($_FILES['ArchivosZip']['type'])){
        
        //if($_FILES['ArchivosZip']['type']=='application/x-zip-compressed'){
            $carpeta="archivos/";
            opendir($carpeta);
            $NombreArchivo=str_replace(' ','_',$_FILES['ArchivosZip']['name']);  
            //$destino=$carpeta.$NombreArchivo;
            //move_uploaded_file($_FILES['ArchivosZip']['tmp_name'],$destino);
            
            $obRips->VerificarZip($_FILES['ArchivosZip']['tmp_name'],$idUser, "");
        //}else{
            //$css->CrearNotificacionRoja("Debe cargar un archivo .zip",16);
            //goto salir;
        //}
            
        $consulta= $obRips->ConsultarTabla("salud_upload_control", " WHERE Analizado='0'");
        $obRips->VaciarTabla("salud_archivo_medicamentos_temp"); //Vacío la tabla de subida temporal
        $obRips->VaciarTabla("salud_archivo_consultas_temp"); //Vacío la tabla de subida temporal
        $obRips->VaciarTabla("salud_archivo_hospitalizaciones_temp"); //Vacío la tabla de subida temporal
        $obRips->VaciarTabla("salud_archivo_procedimientos_temp"); //Vacío la tabla de subida temporal
        $obRips->VaciarTabla("salud_archivo_otros_servicios_temp"); //Vacío la tabla de subida temporal
        $obRips->VaciarTabla("salud_archivo_usuarios_temp"); //Vacío la tabla de subida temporal
        $obRips->VaciarTabla("salud_archivo_nacidos_temp"); //Vacío la tabla de subida temporal
        $obRips->VaciarTabla("salud_rips_facturas_generadas_temp"); //Vacío la tabla de subida temporal
                                                                
        while($DatosArchivos= $obRips->FetchArray($consulta)){
            $NombreArchivo=$DatosArchivos["nom_cargue"]; 
            $Prefijo=substr($NombreArchivo, 0, 2); 
            //Si hay medicamentos
            if($Prefijo=="AM"){
                
                $obRips->InsertarRipsMedicamentos($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                $NumRegistros=$obRips->CalculeRegistros("archivos/".$NombreArchivo,$Separador); // se calculan cuantos registros tiene el archivo
                $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros del archivo $NombreArchivo cargados correctamente",16);

            }
            //Si hay consultas
            if($Prefijo=="AC"){
                $obRips->InsertarRipsConsultas($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                $NumRegistros=$obRips->CalculeRegistros("archivos/".$NombreArchivo,$Separador); // se calculan cuantos registros tiene el archivo
                $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros del archivo $NombreArchivo cargados correctamente",16);

            }
            //Si hay hospitalizaciones 
            if($Prefijo=="AH"){
                $obRips->InsertarRipsHospitalizaciones($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                $NumRegistros=$obRips->CalculeRegistros("archivos/".$NombreArchivo,$Separador); // se calculan cuantos registros tiene el archivo
                $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros del archivo $NombreArchivo cargados correctamente",16);

            }
            //Si hay procedimientos 
            if($Prefijo=="AP"){
                $obRips->InsertarRipsProcedimientos($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                $NumRegistros=$obRips->CalculeRegistros("archivos/".$NombreArchivo,$Separador); // se calculan cuantos registros tiene el archivo
                $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros del archivo $NombreArchivo cargados correctamente",16);

            }
            
            //otros servicios
            if($Prefijo=="AT"){
                $obRips->InsertarRipsOtrosServicios($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                $NumRegistros=$obRips->CalculeRegistros("archivos/".$NombreArchivo,$Separador); // se calculan cuantos registros tiene el archivo
                $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros del archivo $NombreArchivo cargados correctamente",16);

            }
            
            //USUARIOS
            if($Prefijo=="US"){
                $obRips->InsertarRipsUsuarios($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                $NumRegistros=$obRips->CalculeRegistros("archivos/".$NombreArchivo,$Separador); // se calculan cuantos registros tiene el archivo
                $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros del archivo $NombreArchivo cargados correctamente",16);

            }
            //Nacidos
            if($Prefijo=="AN"){
                $obRips->InsertarRipsNacidos($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                $NumRegistros=$obRips->CalculeRegistros("archivos/".$NombreArchivo,$Separador); // se calculan cuantos registros tiene el archivo
                $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros del archivo $NombreArchivo cargados correctamente",16);

            }
            //facturacion generada
            if($Prefijo=="AF"){
                $obRips->InsertarRipsFacturacionGenerada($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                $NumRegistros=$obRips->CalculeRegistros("archivos/".$NombreArchivo,$Separador); // se calculan cuantos registros tiene el archivo
                $css->CrearNotificacionVerde(number_format($NumRegistros)." Registros del archivo $NombreArchivo cargados correctamente",16);

            }
            
        }    
    }
    
}

if(isset($_REQUEST["BtnSubirZip"])){
    $obRips->AnaliceInsercionConsultas(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
    $css->CrearNotificacionNaranja("Tabla de Consultas Analizada",16);
    
    $obRips->AnaliceInsercionHospitalizaciones(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
    $css->CrearNotificacionNaranja("Tabla de Hospitalizaciones Analizada",16);
    
    $obRips->AnaliceInsercionMedicamentos(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
    $css->CrearNotificacionNaranja("Tabla de Medicamentos Analizada",16);
    
    $obRips->AnaliceInsercionProcedimientos(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
    $css->CrearNotificacionNaranja("Tabla de Procedimientos Analizada",16);
    
    $obRips->AnaliceInsercionOtrosServicios(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
    $css->CrearNotificacionNaranja("Tabla de Otros Servicios Analizada",16);
    
    $obRips->AnaliceInsercionUsuarios("");
    $css->CrearNotificacionNaranja("Tabla de Usuarios Analizada",16);
    
    $obRips->AnaliceInsercionNacidos("");
    $css->CrearNotificacionNaranja("Tabla de Nacidos Analizada",16);
    
    $obRips->AnaliceInsercionFacturasGeneradas("");
    $css->CrearNotificacionNaranja("Tabla de Facturas Analizada",16);
    
    $obRips->ModifiqueAutoIncrementables(""); // Se realiza para ajustar los autoincrementables de las tablas tras la importaciosn
}
//salir:       
?>