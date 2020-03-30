<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
include_once("../../modelo/php_conexion.php");
include_once("../css_construct.php");
include_once("../clases/SaludRips.class.php");


$obCon = new Rips($idUser);
if($_REQUEST["idAccion"]){
    $css =  new CssIni("id",0);
    $obRips = new Rips($idUser);
    switch ($_REQUEST["idAccion"]){
        case 1:
                        
            $CuentaRIPS=$obCon->normalizar($_REQUEST["CuentaRIPS"]);
            $CuentaRIPS=str_pad($CuentaRIPS, 6, "0", STR_PAD_LEFT);
            $sql="SELECT CuentaRIPS FROM salud_archivo_facturacion_mov_generados WHERE CuentaRIPS='$CuentaRIPS' LIMIT 1";
            $consulta=$obCon->Query($sql);
            $DatosConsulta=$obCon->FetchArray($consulta);
            if($DatosConsulta["CuentaRIPS"]==$CuentaRIPS){
                print("OK");
            }else{
                print("OK");
            }
            
        break;
        case 2: //Subir al servidor el .zip con los archivos
            $obCon->VaciarArchivosTemporalesCarga();            
            if(!empty($_FILES['ArchivosZip']['type'])){

                $carpeta="../archivos/";
                //opendir($carpeta);
                $NombreArchivo=str_replace(' ','_',$_FILES['ArchivosZip']['name']); 

                $obRips->VerificarZip($_FILES['ArchivosZip']['tmp_name'],$idUser, "");
                print("OK");
            }else{
                print("Error No fue recibido el archivo .zip");
            }
            
        break;  
        case 3://Se borran los archivos del upload control 
            $obCon->VaciarArchivosTemporalesCarga();
            print("OK");
            
        break;
    
        case 4://Verificar CT Y Verificar que los archivos correspondan al numero de cuenta RIPS
            print("OK");
            exit();
            $CuentaRIPS=str_pad($obCon->normalizar($_REQUEST["CuentaRIPS"]), 6, "0", STR_PAD_LEFT);
            //print($CuentaRIPS);
            $ErrorCT=1;
            $ErrorArchivos=0;
            $consulta= $obRips->ConsultarTabla("salud_upload_control", " WHERE Analizado='0' AND nom_cargue LIKE 'CT%'");
            while($DatosCT= $obRips->FetchArray($consulta)){
                if($DatosCT["nom_cargue"]<>""){
                    $ErrorCT=0;
                }
            }
            if($ErrorCT==1){
                $css->CrearNotificacionRoja("No se recibió el Archivo CT", 14);
                //exit();
            }
            
            $consulta= $obRips->ConsultarTabla("salud_upload_control", " WHERE Analizado='0'");
            
            while($DatosArchivos= $obRips->FetchArray($consulta)){
                
                $NumCuentaRIPS=substr($DatosArchivos["nom_cargue"], 2, 6);
                //print($NumCuentaRIPS);
                if($NumCuentaRIPS<>$CuentaRIPS){
                    $ErrorArchivos=1;
                    $css->CrearNotificacionRoja("<br>La Cuenta RIPS Digitada No coincide con el archivo ".$DatosArchivos["nom_cargue"], 14);   
                    exit();
                    
                }
                
            }
            if($ErrorArchivos==0 AND $ErrorCT==0){
                print("OK");
            }else{
                print(" ");
            }
            
        break;
        case 5://Devuelve los nombres de los archivos que se guardaron  en la carpeta y se verifica que correspondan al CT
            
            $Separador=$_REQUEST["CmbSeparador"];
            $Error=0;
            $consulta = $obRips->ConsultarTabla("salud_upload_control", " WHERE Analizado='0' AND nom_cargue LIKE 'CT%' ORDER BY id_upload_control DESC");
            while($DatosCT= $obRips->FetchArray($consulta)){
                
                if (file_exists("../archivos/".$DatosCT["nom_cargue"])) {
                    $handle = fopen("../archivos/".$DatosCT["nom_cargue"], "r");
                    $flagCTSinRegistros=1;
                    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                        $flagCTSinRegistros=0;
                        $NombreArchivo=$data[2].".txt";
                        $Datos["nom_cargue"]=$NombreArchivo;
                        $sql=$obCon->getSQLInsert("salud_upload_control_ct", $Datos);
                        $obCon->Query($sql);
                        
                    }
                    fclose($handle); 
                    //Se valida si se relacionaron Archivos que no estan en el paquete
                    $sql='SELECT nom_cargue
                            FROM salud_upload_control
                           WHERE nom_cargue NOT IN (SELECT nom_cargue
                       FROM salud_upload_control_ct) AND Analizado=0 AND nom_cargue NOT LIKE "CT%"';
                            
                    $consulta=$obCon->Query($sql);
                    while($DatosUpl=$obCon->FetchAssoc($consulta)){
                        print("<strong style='color:red'>El Archivo ".$DatosUpl["nom_cargue"]." No está Relacionado en el CT </strong> <br>");
                    
                        $Error=1;
                    }
                    //Se valida si se relacionaron Archivos que no estan en el paquete
                    $sql='SELECT nom_cargue
                            FROM salud_upload_control_ct
                           WHERE nom_cargue NOT IN (SELECT nom_cargue
                       FROM salud_upload_control) AND nom_cargue NOT LIKE "CT%"';
                            
                    $consulta=$obCon->Query($sql);
                    while($DatosUpl=$obCon->FetchAssoc($consulta)){
                        print("<strong style='color:red'>El Archivo ".$DatosUpl["nom_cargue"]." Está Relacionado en el CT pero no se subió</strong> <br>");
                    
                        $Error=1;
                    }
                    
                }else{
                    exit("No existe el archivo ../archivos/".$DatosCT["nom_cargue"]);
                }
                                
            }
            $ArchivoCT=$DatosCT["nom_cargue"];
            //$sql="DELETE FROM salud_upload_control WHERE nom_cargue LIKE 'CT%'";
            //$obCon->Query($sql);
            if($Error==0){
                print("OK");
            }
            
        break;  
        case 6://Cargo temporal AF
            //print("entra");
            $Error=0;  
            $ErrorAF=1;
            $idEPS=$obRips->normalizar($_REQUEST["idEPS"]);
            $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
            $CuentaGlobal=$obRips->normalizar($_REQUEST["CuentaGlobal"]);
            $FechaRadicado=$obRips->normalizar($_REQUEST["FechaRadicado"]);
            $NumeroRadicado=$obRips->normalizar($_REQUEST["NumeroRadicado"]);
            $CmbEscenario=$obRips->normalizar($_REQUEST["CmbEscenario"]);
            $TipoNegociacion=$obRips->normalizar($_REQUEST["CmbTipoNegociacion"]);
            $CuentaRIPS=$obRips->normalizar($_REQUEST["CuentaRIPS"]);
            $CuentaContable=$obRips->normalizar($_REQUEST["CmbCuentaContable"]);
            if($TipoNegociacion=='capita'){
                $DatosCuentaGlobal=$obCon->DevuelveValores("salud_archivo_facturacion_mov_generados", "num_factura", $CuentaGlobal);
                if($DatosCuentaGlobal["num_factura"]<>''){
                    $css->CrearNotificacionRoja("Error, la CuentaGlobal $CuentaGlobal ya está asociada en el registro ".$DatosCuentaGlobal["id_fac_mov_generados"]." del AF", 14);
                    exit();
                }
            }
            
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
            
            
            $FechaCargue=date("Y-m-d H:i:s");
            
            $consulta = $obRips->ConsultarTabla("salud_upload_control", " WHERE CargadoTemp='0' AND nom_cargue LIKE 'AF%' ORDER BY id_upload_control DESC");
            while($DatosArchivo=$obCon->FetchArray($consulta)){
                $Prefijo=substr($DatosArchivo["nom_cargue"], 0, 2); 
                if($Prefijo=="AF"){
                    $ErrorAF=0;
                    $MensajeInsercion=$obRips->InsertarRipsFacturacionGenerada($DatosArchivo["nom_cargue"], $TipoNegociacion, $Separador, $FechaCargue, $idUser,$destino,$FechaRadicado,$NumeroRadicado,$CmbEscenario,$CuentaGlobal,$CuentaRIPS,$idEPS,$CuentaContable, "");
                    if($MensajeInsercion["Errores"]>0){
                        $Error=1;
                        $Mensaje["Error"]["Num"]=$MensajeInsercion["Errores"];
                        $Mensaje["Error"]["Lines"]=$MensajeInsercion["LineasError"];
                        $Mensaje["Error"]["Pos"]=$MensajeInsercion["PosError"];
                        //$css->CrearNotificacionRoja("Error en el Archivo AF, la EPS o IPS no corresponde al archivo subido, Linea:".$MensajeInsercion["LineasError"], 14);
                    }
                }
                
            }
            if($ErrorAF==1){
                $css->CrearNotificacionRoja("Error, la CuentaRIPS no corresponde al paquete enviado", 14);
                exit();
            }
            if($Error==0){
                print("OK");
            }
        break;    
        case 7://Cargo el AC en la Temporal
            $TipoNegociacion=$obRips->normalizar($_REQUEST["CmbTipoNegociacion"]);
            $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
            $FechaCargue=date("Y-m-d H:i:s");
            $consulta = $obRips->ConsultarTabla("salud_upload_control", " WHERE CargadoTemp='0' AND nom_cargue LIKE 'AC%'");
            while($DatosArchivo=$obCon->FetchArray($consulta)){
                $Prefijo=substr($DatosArchivo["nom_cargue"], 0, 2); 
                if($Prefijo=="AC"){

                    $obRips->VaciarTabla("salud_archivo_consultas_temp"); //Vacío la tabla de subida temporal
                    $obRips->InsertarRipsConsultas($DatosArchivo["nom_cargue"], $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");

                }
            }
            print("OK");
        break;
        
        case 8://Cargo el AP en la Temporal
            $TipoNegociacion=$obRips->normalizar($_REQUEST["CmbTipoNegociacion"]);
            $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
            $FechaCargue=date("Y-m-d H:i:s");
            $consulta = $obRips->ConsultarTabla("salud_upload_control", " WHERE CargadoTemp='0' AND nom_cargue LIKE 'AP%'");
            while($DatosArchivo=$obCon->FetchArray($consulta)){
                $Prefijo=substr($DatosArchivo["nom_cargue"], 0, 2); 
                if($Prefijo=="AP"){

                    $obRips->VaciarTabla("salud_archivo_procedimientos_temp"); //Vacío la tabla de subida temporal                
                    $obRips->InsertarRipsProcedimientos($DatosArchivo["nom_cargue"], $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
               
                }
            }
            print("OK");
        break;
        
        case 9://Cargo el AT en la Temporal
            $TipoNegociacion=$obRips->normalizar($_REQUEST["CmbTipoNegociacion"]);
            $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
            $FechaCargue=date("Y-m-d H:i:s");
            $consulta = $obRips->ConsultarTabla("salud_upload_control", " WHERE CargadoTemp='0' AND nom_cargue LIKE 'AT%'");
            while($DatosArchivo=$obCon->FetchArray($consulta)){
                $Prefijo=substr($DatosArchivo["nom_cargue"], 0, 2); 
                if($Prefijo=="AT"){

                    $obRips->VaciarTabla("salud_archivo_otros_servicios_temp"); //Vacío la tabla de subida temporal
                    $obRips->InsertarRipsOtrosServicios($DatosArchivo["nom_cargue"], $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                
                }
            }
            print("OK");
        break;
        
        case 10://Cargo el AM en la Temporal
            $TipoNegociacion=$obRips->normalizar($_REQUEST["CmbTipoNegociacion"]);
            $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
            $FechaCargue=date("Y-m-d H:i:s");
            $consulta = $obRips->ConsultarTabla("salud_upload_control", " WHERE CargadoTemp='0' AND nom_cargue LIKE 'AM%'");
            while($DatosArchivo=$obCon->FetchArray($consulta)){
                $Prefijo=substr($DatosArchivo["nom_cargue"], 0, 2); 
                if($Prefijo=="AM"){

                    $obRips->VaciarTabla("salud_archivo_medicamentos_temp"); //Vacío la tabla de subida temporal
                    $obRips->InsertarRipsMedicamentos($DatosArchivo["nom_cargue"], $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
               
                }
            }
            print("OK");
        break;
        
        case 11://Cargo el AH en la Temporal
            $TipoNegociacion=$obRips->normalizar($_REQUEST["CmbTipoNegociacion"]);
            $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
            $FechaCargue=date("Y-m-d H:i:s");
            $consulta = $obRips->ConsultarTabla("salud_upload_control", " WHERE CargadoTemp='0' AND nom_cargue LIKE 'AH%'");
            while($DatosArchivo=$obCon->FetchArray($consulta)){
                $Prefijo=substr($DatosArchivo["nom_cargue"], 0, 2); 
                if($Prefijo=="AH"){

                    $obRips->VaciarTabla("salud_archivo_hospitalizaciones_temp"); //Vacío la tabla de subida temporal
                    $obRips->InsertarRipsHospitalizaciones($DatosArchivo["nom_cargue"], $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                
                }
            }
            print("OK");
        break;
        
        case 12://Cargo el US en la Temporal
            $TipoNegociacion=$obRips->normalizar($_REQUEST["CmbTipoNegociacion"]);
            $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
            $FechaCargue=date("Y-m-d H:i:s");
            $consulta = $obRips->ConsultarTabla("salud_upload_control", " WHERE CargadoTemp='0' AND nom_cargue LIKE 'US%'");
            while($DatosArchivo=$obCon->FetchArray($consulta)){
                $Prefijo=substr($DatosArchivo["nom_cargue"], 0, 2); 
                if($Prefijo=="US"){

                    $obRips->VaciarTabla("salud_archivo_usuarios_temp"); //Vacío la tabla de subida temporal
                    $obRips->InsertarRipsUsuarios($DatosArchivo["nom_cargue"], $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                
                }
            }
            print("OK");
        break;
        
        case 13://Cargo el AN en la Temporal
            $TipoNegociacion=$obRips->normalizar($_REQUEST["CmbTipoNegociacion"]);
            $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
            $FechaCargue=date("Y-m-d H:i:s");
            $consulta = $obRips->ConsultarTabla("salud_upload_control", " WHERE CargadoTemp='0' AND nom_cargue LIKE 'AN%'");
            while($DatosArchivo=$obCon->FetchArray($consulta)){
                $Prefijo=substr($DatosArchivo["nom_cargue"], 0, 2); 
                if($Prefijo=="AN"){

                    $obRips->VaciarTabla("salud_archivo_nacidos_temp"); //Vacío la tabla de subida temporal
                    $obRips->InsertarRipsNacidos($DatosArchivo["nom_cargue"], $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                
                }
            }
            print("OK");
        break;
        
        case 14://Cargo el AU en la Temporal
            $TipoNegociacion=$obRips->normalizar($_REQUEST["CmbTipoNegociacion"]);
            $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
            $FechaCargue=date("Y-m-d H:i:s");
            $consulta = $obRips->ConsultarTabla("salud_upload_control", " WHERE CargadoTemp='0' AND nom_cargue LIKE 'AU%'");
            while($DatosArchivo=$obCon->FetchArray($consulta)){
                $Prefijo=substr($DatosArchivo["nom_cargue"], 0, 2); 
                if($Prefijo=="AU"){

                    $obRips->VaciarTabla("salud_archivo_urgencias_temp"); //Vacío la tabla de subida temporal
                    $obRips->InsertarRipsUrgencias($DatosArchivo["nom_cargue"], $TipoNegociacion, $Separador, $FechaCargue, $idUser, "");
                
                }
            }
            print("OK");
        break;
        
        case 15://Analice los AF        
            $CuentaGlobal=$obRips->normalizar($_REQUEST["CuentaGlobal"]);
            $ValorCapita=$obRips->normalizar($_REQUEST["ValorCapita"]);
            $Vector["CuentaGlobal"]=$CuentaGlobal;
            $Vector["ValorCapita"]=$ValorCapita;
            $Error=$obRips->AnaliceInsercionFacturasGeneradas($Vector); 
            if($Error=="E1"){
                exit("Error, No se recibió el valor de la capita");
            }
            if($Error=="E2"){
                exit("Error, No se recibió la Cuenta Global");
            }
            $obRips->VaciarTabla("salud_rips_facturas_generadas_temp");
            
            print("OK");
        break;
        case 16://Analice los AC            
            $obRips->AnaliceInsercionConsultas(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
            $obRips->VaciarTabla("salud_archivo_consultas_temp");   
            print("OK");
        break;
        case 17://Analice los AP  
            $obRips->AnaliceInsercionProcedimientos(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
            $obRips->VaciarTabla("salud_archivo_procedimientos_temp");   
               
            print("OK");
        break;
        case 18://Analice los AM            
            $obRips->AnaliceInsercionMedicamentos(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
            $obRips->VaciarTabla("salud_archivo_medicamentos_temp");   
             
            print("OK");
        break;
        case 19://Analice los AT
            $obRips->AnaliceInsercionOtrosServicios(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
            $obRips->VaciarTabla("salud_archivo_otros_servicios_temp");   
             
            print("OK");
        break;
        case 20://Analice los AH          
            $obRips->AnaliceInsercionHospitalizaciones(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
            $obRips->VaciarTabla("salud_archivo_hospitalizaciones_temp"); 
            print("OK");
        break;
        case 21://Analice los US           
            $obRips->AnaliceInsercionUsuarios(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
            $obRips->VaciarTabla("salud_archivo_usuarios_temp"); 
              
            print("OK");
        break;
        case 22://Analice los AN           
            $obRips->AnaliceInsercionNacidos(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
            $obRips->VaciarTabla("salud_archivo_nacidos_temp");            
            print("OK");
        break;
        case 23://Analice los AU            
            $obRips->AnaliceInsercionUrgencias(""); //Analizamos la tabla temporal que se sube y se inserta en la principal
            $obRips->VaciarTabla("salud_archivo_urgencias_temp");            
            print("OK");
        break;
        case 24://Modifica los autoincrementables
            $obRips->ModifiqueAutoIncrementables("");
            $obCon->update("salud_upload_control", "Analizado", 1, "");
            //$obCon->update("salud_upload_control", "CargadoTemp", 1, "");
            print("OK");
        break;
        case 25://Verifica duplicados
            $Error=$obRips->VerifiqueDuplicadosAF(""); // Verifica si hay duplicados en los AF subidos
            
            if($Error==1){
                print("Error");
            }
            if($Error==0){
                print("OK");   
            }
        break;
        case 26://Verifica si hay devoluciones duplicadas
            
            $sql="SELECT ID FROM vista_af_devueltos LIMIT 1";
            $consulta=$obCon->Query($sql);
            $DatosConsulta=$obCon->FetchArray($consulta);
            if($DatosConsulta["ID"]>0){
                print("SI");
            }else{
                print("NO"); 
            }
            
        break;
        case 27://Verifica si hay devoluciones duplicadas
            
            $obCon->ActualiceAFDevueltas("");
            print("OK");
            
        break;
    
        case 28://Verifica el valor de la cuenta
            
            $Total=$obCon->Sume("salud_rips_facturas_generadas_temp", "valor_neto_pagar", "");
            print("OK;$Total");
            
        break;
        
               
    }
    
}else{
    
    print("No se recibieron parametros");
}
    
    

?>