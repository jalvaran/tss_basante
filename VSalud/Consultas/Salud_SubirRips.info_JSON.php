<?php
//header('Content-type: application/json');
session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
include_once("../../modelo/php_conexion.php");
include_once("../clases/SaludRips.class.php");


$obCon = new conexion($idUser);
if($_REQUEST["idAccion"]){
    $obRips = new Rips($idUser);
    switch ($_REQUEST["idAccion"]){
        case 1: //Subir al servidor el .zip con los archivos
            $Mensaje["msg"]="OK";
            $idEPS=$obRips->normalizar($_REQUEST["idEPS"]);
            $CuentaRIPS=$obRips->normalizar($_REQUEST["CuentaRIPS"]);
            $FechaRadicado=$obRips->normalizar($_REQUEST["FechaRadicado"]);
            $CuentaRIPS=str_pad($CuentaRIPS, 6, "0", STR_PAD_LEFT);
            $Mensaje["Separador"]=$obRips->normalizar($_REQUEST["CmbSeparador"]);
            if($Mensaje["Separador"]==1){
                $Mensaje["Separador"]=";";
            }else{
                $Mensaje["Separador"]=",";
            }
            $Mensaje["TipoNegociacion"]=$obRips->normalizar($_REQUEST["CmbTipoNegociacion"]);
            $FechaCargue=date("Y-m-d H:i:s");
            $Archivos="";
            if(!empty($_FILES['ArchivosZip']['type'])){

                $carpeta="../archivos/";
                opendir($carpeta);
                $NombreArchivo=str_replace(' ','_',$_FILES['ArchivosZip']['name']); 

                $Archivos=$obRips->VerificarZip($_FILES['ArchivosZip']['tmp_name'],$idUser, "");
            }
            $consulta= $obRips->ConsultarTabla("salud_upload_control", " WHERE Analizado='0'");
            $i=0;
            $Mensaje["CT"]=0;
            $NumCuentaRIPS=0;
            while($DatosArchivos= $obRips->FetchArray($consulta)){
                $Prefijo=substr($DatosArchivos["nom_cargue"], 0, 2);
                if($Prefijo=="CT"){
                    $Mensaje["CT"]=1;
                    $NumCuentaRIPS=substr($DatosArchivos["nom_cargue"], 2, 6);
                    if($NumCuentaRIPS<>$CuentaRIPS){
                        $obRips->BorraReg("salud_upload_control", "Analizado", 0);
                        $Mensaje["msg"]="ErrorCuentaRIPS";
                    }
                }
                
                $Mensaje["Archivos"][$i]=$DatosArchivos["nom_cargue"];
                $i++;
            }
            
            $fecha_actual = strtotime(date("Y-m-d"));
            $fecha_entrada = strtotime($FechaRadicado);
            if($fecha_entrada>$fecha_actual){
                $Mensaje["ErrorFecha"]=1;
            }
            
            $Mensaje["CuentaRIPSCT"]=$NumCuentaRIPS;
            print(json_encode($Mensaje));
        break;
        case 2://Devuelve los nombres de los archivos que se guardaron  en la carpeta y se verifica que correspondan al CT
            $Mensaje["msg"]="OK";
            $Mensaje["Errores"]=0;
            $Separador=$_REQUEST["Separador"];
            $Mensaje["Separador"]=$Separador;
            $Mensaje["ArchivosNE"]="";
            $consulta = $obRips->ConsultarTabla("salud_upload_control", " WHERE Analizado='0' AND nom_cargue LIKE 'CT%'");
            while($DatosCT= $obRips->FetchArray($consulta)){
                //print("Archivo".$DatosCT["nom_cargue"]);
                $handle = fopen("../archivos/".$DatosCT["nom_cargue"], "r");
                while (($data = fgetcsv($handle, 1000, $Separador)) !== FALSE) {
                    $NombreArchivo=$data[2].".txt";
                    $DatosCarga=$obRips->DevuelveValores("salud_upload_control", "nom_cargue", $NombreArchivo);
                    if($DatosCarga["id_upload_control"]==""){
                        $Mensaje["Errores"]++;
                        $id=$Mensaje["Errores"];
                        $Mensaje["ArchivosNE"][$id]=$NombreArchivo;
                    }
                }
                fclose($handle); 
            }
            
            print(json_encode($Mensaje));
        break;
        case 3:// Grabar archivos en temporales
            $Mensaje["fin"]=0;
            $Mensaje["msg"]="OK";
            $idEPS=$obRips->normalizar($_REQUEST["idEPS"]);
            $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
            $CuentaGlobal=$obRips->normalizar($_REQUEST["CuentaGlobal"]);
            $FechaRadicado=$obRips->normalizar($_REQUEST["FechaRadicado"]);
            $NumeroRadicado=$obRips->normalizar($_REQUEST["NumeroRadicado"]);
            $CmbEscenario=$obRips->normalizar($_REQUEST["CmbEscenario"]);
            $NombreArchivo=$obRips->normalizar($_REQUEST["NombreArchivo"]);
            $CuentaRIPS=$obRips->normalizar($_REQUEST["CuentaRIPS"]);
            
            $TipoNegociacion=$obRips->normalizar($_REQUEST["CmbTipoNegociacion"]);
            $FechaCargue=date("Y-m-d H:i:s");
            $Prefijo=substr($NombreArchivo, 0, 2); 
            
            if($Prefijo=="AF"){
                $destino="";
                if(!empty($_FILES['UpSoporteRadicado']['name'])){
                    //echo "<script>alert ('entra foto')</script>";
                    $Atras="../";
                    $carpeta="SoportesSalud/SoportesRadicados/";
                    opendir($Atras.$Atras.$carpeta);
                    $Name=str_replace(' ','_',$NumeroRadicado."_".$_FILES['UpSoporteRadicado']['name']);  
                    $destino=$carpeta.$Name;
                    move_uploaded_file($_FILES['UpSoporteRadicado']['tmp_name'],$Atras.$Atras.$destino);
                }
                $obRips->VaciarTabla("salud_rips_facturas_generadas_temp"); //Vacío la tabla de subida temporal
                $MensajeInsercion=$obRips->InsertarRipsFacturacionGenerada($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser,$destino,$FechaRadicado,$NumeroRadicado,$CmbEscenario,$CuentaGlobal,$CuentaRIPS,$idEPS, "");
                
                if($MensajeInsercion["Errores"]>0){
                    $Mensaje["msg"]="Error";
                    $Mensaje["Error"]["Num"]=$MensajeInsercion["Errores"];
                    $Mensaje["Error"]["Lines"]=$MensajeInsercion["LineasError"];
                    $Mensaje["Error"]["Pos"]=$MensajeInsercion["PosError"];
                }
                 
                
            }
            if($Prefijo=="AM"){
                
                $obRips->VaciarTabla("salud_archivo_medicamentos_temp"); //Vacío la tabla de subida temporal
                $obRips->InsertarRipsMedicamentos($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                
            }
            if($Prefijo=="AC"){
                
                $obRips->VaciarTabla("salud_archivo_consultas_temp"); //Vacío la tabla de subida temporal
                $obRips->InsertarRipsConsultas($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                
            }
            if($Prefijo=="AH"){
                $obRips->VaciarTabla("salud_archivo_hospitalizaciones_temp"); //Vacío la tabla de subida temporal
                $obRips->InsertarRipsHospitalizaciones($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                
            }
            if($Prefijo=="AP"){
                
                $obRips->VaciarTabla("salud_archivo_procedimientos_temp"); //Vacío la tabla de subida temporal                
                $obRips->InsertarRipsProcedimientos($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                
            }
            
            if($Prefijo=="AT"){
                
                $obRips->VaciarTabla("salud_archivo_otros_servicios_temp"); //Vacío la tabla de subida temporal
                $obRips->InsertarRipsOtrosServicios($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                
            }
            if($Prefijo=="US"){
                
                $obRips->VaciarTabla("salud_archivo_usuarios_temp"); //Vacío la tabla de subida temporal
                $obRips->InsertarRipsUsuarios($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                
            }
            if($Prefijo=="AN"){
                
                $obRips->VaciarTabla("salud_archivo_nacidos_temp"); //Vacío la tabla de subida temporal
                $obRips->InsertarRipsNacidos($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                
            }
            if($Prefijo=="AU"){
                
                $obRips->VaciarTabla("salud_archivo_urgencias_temp"); //Vacío la tabla de subida temporal
                $obRips->InsertarRipsUrgencias($NombreArchivo, $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                
            }
                   
            print(json_encode($Mensaje));
        break;  
        case 4:// Analice tablas
            $NombreArchivo=$obRips->normalizar($_REQUEST["Archivo"]);
            $Prefijo=substr($NombreArchivo, 0, 2);
            if($Prefijo=="AF"){
                $obRips->AnaliceInsercionFacturasGeneradas("");
            }
            if($Prefijo=="AC"){
                $obRips->AnaliceInsercionConsultas(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
    
            }
            if($Prefijo=="AH"){
                $obRips->AnaliceInsercionHospitalizaciones(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
    
            }
            if($Prefijo=="AM"){
                $obRips->AnaliceInsercionMedicamentos(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
    
            }
            if($Prefijo=="AP"){
                $obRips->AnaliceInsercionProcedimientos(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
    
            }
            if($Prefijo=="AT"){
                $obRips->AnaliceInsercionOtrosServicios(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
    
            }
            if($Prefijo=="US"){
                $obRips->AnaliceInsercionUsuarios(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
    
            }
            if($Prefijo=="AN"){
                $obRips->AnaliceInsercionNacidos(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
            }
            if($Prefijo=="AU"){
                $obRips->AnaliceInsercionUrgencias(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
            }
            $Mensaje["msg"]="OK";            
            print(json_encode($Mensaje));
        break;    
        case 5: //Modifica los autoincrementables
            $obRips->ModifiqueAutoIncrementables(""); // Se realiza para ajustar los autoincrementables de las tablas tras la importaciosn
            $Mensaje["msg"]="OK";            
            print(json_encode($Mensaje));
        break;
        case 6: //Verificar si hay facturas repetidas y si las hay no las cargue y muestre en que linea
            $Error=$obRips->VerifiqueDuplicadosAF(""); // Verifica si hay duplicados en los AF subidos
            if($Error==1){
                $Mensaje["msg"]="Error";
            }
            if($Error==0){
                $Mensaje["msg"]="OK";   
            }
                  
            print(json_encode($Mensaje));
        break;
        case 7: //Eliminar la ultima carga para cuando se produzcan errores
            
            print(json_encode($Mensaje));
        break;
    }
    
}else{
    $Mensaje["msg"]="Error";
    $Mensaje["error"]="No se ha seleccionado la EPS";
    print(json_encode($Mensaje));
}
    
    

?>