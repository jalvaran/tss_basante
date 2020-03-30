<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];

include_once("../../modelo/php_conexion.php");
include_once("../css_construct.php");
include_once("../clases/GlosasCargasMasivas.class.php");


if( !empty($_REQUEST["idAccion"]) ){
    $css =  new CssIni("id",0);
    $obGlosas = new GlosasMasivas($idUser);
    
    switch ($_REQUEST["idAccion"]) {
        case 1: //Recibe el archivo
            $Fecha=date("Y-m-d");
            $destino='';
            $Name='';
            $Extension="";
            if(!empty($_FILES['UpCargaMasivaConciliaciones']['name'])){
                
                $info = new SplFileInfo($_FILES['UpCargaMasivaConciliaciones']['name']);
                $Extension=($info->getExtension());
                $Atras="../";
                $carpeta="SoportesSalud/CargasMasivasGlosas/";
                opendir($Atras.$Atras.$carpeta);
                $Name=str_replace(' ','_','Conciliacion_'.$Fecha."_".$_FILES['UpCargaMasivaConciliaciones']['name']);
                $destino=$carpeta.$Name;
                move_uploaded_file($_FILES['UpCargaMasivaConciliaciones']['tmp_name'],$Atras.$Atras.$destino);
            }
            $obGlosas->RegistreArchivoSubido($Fecha, $destino,$Extension, $idUser);
            print("OK");
        break;
        case 2://Borra la carga del ultimo archivo en caso de errores
            $sql="DELETE FROM salud_control_glosas_masivas WHERE Analizado=0";
            $obGlosas->Query($sql);
            $obGlosas->VaciarTabla("salud_conciliaciones_masivas_temp");
            print("Temporales borrados");
        break; 
        case 3://Leer Archivo y llevarlo a la tabla temporal            
            $obGlosas->LeerArchivoConciliaciones("");
            print("OK");
        break; 
        case 4://Se realizan las validaciones
            $Parametros=$obGlosas->DevuelveValores("salud_parametros_generales", "ID", 1);
            $Errores=0;
            $sql="UPDATE salud_conciliaciones_masivas_temp t1 INNER JOIN salud_archivo_facturacion_mov_generados t2 ON t1.num_factura=t2.num_factura 
                    SET t1.CuentaRIPS=t2.CuentaRIPS;";
            $obGlosas->Query($sql);
            
            $sql="SELECT * FROM vista_salud_consolidaciones_masivas";
            $Datos=$obGlosas->Query($sql);
            while($DatosCarga=$obGlosas->FetchArray($Datos)){
                if($DatosCarga["Extemporanea"]==1){ //Si la fecha es mayor a la actual
                    $css->CrearNotificacionRoja("<br>Error: la Fecha No puede ser mayor a la fecha actual, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                if($DatosCarga["Factura"]==''){ //Si existe la factura en los AF
                    $css->CrearNotificacionRoja("<br>Error: la factura No existe en los registros AF, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                if($DatosCarga["CuentaRIPS"]==''){ //Si existe la factura en los AF
                    $css->CrearNotificacionRoja("<br>Error: la Factura no está asociada a la CuentaRIPS, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                
                if(($DatosCarga["CodigoActividadAM"]=='') AND ($DatosCarga["CodigoActividadAT"]=='') AND ($DatosCarga["CodigoActividadAP"]=='') AND ($DatosCarga["CodigoActividadAC"]=='') ){ //Si la actividad no existe en ninguno de los archivos
                    $css->CrearNotificacionRoja("<br>Error: La factura no contiene el codigo de la actividad relacionada, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                
                $idFactura=$DatosCarga["Factura"];
                $idActividad=$DatosCarga["CodigoActividad"];
                $TotalGlosasExistentes=$obGlosas->Sume("salud_glosas_iniciales", "ValorGlosado", " WHERE num_factura='$idFactura' AND CodigoActividad='$idActividad' AND EstadoGlosa<>13");
                $TotalConciliacion=$DatosCarga["ValorLevantado"]+$DatosCarga["ValorAceptado"];
                if($TotalGlosasExistentes<>$TotalConciliacion){
                    $css->CrearNotificacionRoja("<br>Error: El total Glosado no concuerda con el valor conciliado, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                if($DatosCarga["ValorLevantadoPositivo"]==0){ //Si la fecha es mayor a la actual
                    $css->CrearNotificacionRoja("<br>Error: El Valor levantado no es positivo, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                if($DatosCarga["ValorAceptadoPositivo"]==0){ //Si la fecha es mayor a la actual
                    $css->CrearNotificacionRoja("<br>Error: El Valor aceptado no es positivo, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                $Estado='';
                if($DatosCarga["CodigoActividadAM"]<>''){ //Si la fecha es mayor a la actual
                    $Estado=$DatosCarga["EstadoGlosaAM"];
                }
                if($DatosCarga["CodigoActividadAP"]<>''){ //Si la fecha es mayor a la actual
                    $Estado=$DatosCarga["EstadoGlosaAP"];
                }
                if($DatosCarga["CodigoActividadAC"]<>''){ //Si la fecha es mayor a la actual
                    $Estado=$DatosCarga["EstadoGlosaAC"];
                }
                if($DatosCarga["CodigoActividadAT"]<>''){ //Si la fecha es mayor a la actual
                    $Estado=$DatosCarga["EstadoGlosaAT"];
                }
                if($Estado>4){ //Se revisa que el estado de la actividad indique si  está glosada o no
                    $css->CrearNotificacionRoja("<br>Error: La actividad no está Glosada o ya está conciliada, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
            }
            
            $sql="SELECT COUNT(*) as Repetidos FROM salud_conciliaciones_masivas_temp GROUP BY num_factura,CodigoActividad HAVING COUNT(*) > 1 LIMIT 1";
            $Datos=$obGlosas->Query($sql);
            while($DatosCarga=$obGlosas->FetchArray($Datos)){
                if($DatosCarga["Repetidos"]>1){
                    $css->CrearNotificacionRoja("<br>Error: El Archivo Subido contiene Registros donde se repiten el Numero de factura y el Codigo de Actividad ",14);
                    $Errores=$Errores+1;
                }
            }
            if($Errores==0){
                $obGlosas->update("salud_glosas_masivas_temp", "Analizado", 1, " WHERE Analizado=0");
                print("OK");
            }else{
                print("<br>Total de Errores: $Errores");                   
                
            }
            
            
        break;
        case 5://Guarda las Consolidaciones
            $sql="SELECT COUNT(*) as Total FROM salud_conciliaciones_masivas_temp";
            $Datos=$obGlosas->Query($sql);
            $Datos=$obGlosas->FetchArray($Datos);
            $TotalGlosas=$Datos["Total"];
            $Datos=$obGlosas->ConsultarTabla("vista_salud_consolidaciones_masivas", "WHERE Conciliada=0 LIMIT 1");
            $DatosGlosa=$obGlosas->FetchArray($Datos);
            $ValorActividad=$DatosGlosa["TotalAM"]+$DatosGlosa["TotalAT"]+$DatosGlosa["TotalAP"]+$DatosGlosa["TotalAC"];
            $TipoArchivo="";
            
            if($DatosGlosa["CodigoActividadAM"]<>''){
                $TipoArchivo="AM";
                $NombreActividad=$DatosGlosa["NombreActividadAM"];
            }
            if($DatosGlosa["CodigoActividadAC"]<>''){
                $TipoArchivo="AC";
                $NombreActividad=$DatosGlosa["NombreActividad"];
            }
            if($DatosGlosa["CodigoActividadAT"]<>''){
                $TipoArchivo="AT";
                $NombreActividad=$DatosGlosa["NombreActividadAT"];
            }
            if($DatosGlosa["CodigoActividadAP"]<>''){
                $TipoArchivo="AP";
                $NombreActividad=$DatosGlosa["NombreActividad"];
            }
            $idFactura=$DatosGlosa["Factura"];  
            $idActividad=$DatosGlosa["CodigoActividad"];
            $TotalGlosasExistentes=$obGlosas->Sume("salud_glosas_iniciales", "ValorGlosado", " WHERE num_factura='$idFactura' AND CodigoActividad='$idActividad'");
            $ValorGlosado=$DatosGlosa["ValorAceptado"]+$DatosGlosa["ValorLevantado"];
            $sql="UPDATE salud_archivo_control_glosas_respuestas SET EstadoGlosa=13 WHERE num_factura='$idFactura' AND CodigoActividad='$idActividad'";
            $obGlosas->Query($sql);
            $sql="UPDATE salud_glosas_iniciales SET EstadoGlosa=13 WHERE num_factura='$idFactura' AND CodigoActividad='$idActividad'";
            $obGlosas->Query($sql);
            
            $idGlosa=$obGlosas->RegistrarGlosaInicialConciliada(6, $idFactura, $idActividad, $ValorActividad, $DatosGlosa["FechaConciliacion"], $DatosGlosa["FechaConciliacion"], '', $ValorGlosado, $DatosGlosa["ValorAceptado"], 0, $DatosGlosa["ValorLevantado"], "");
            $obGlosas->RegistraGlosaRespuesta($TipoArchivo, $idGlosa, $idFactura, $idActividad, $NombreActividad, $ValorActividad, 6, $DatosGlosa["FechaConciliacion"], $DatosGlosa["FechaConciliacion"], $DatosGlosa["Observaciones"], '', $ValorGlosado, $DatosGlosa["ValorAceptado"], $DatosGlosa["ValorLevantado"], 0, $DatosGlosa["Soporte"], $idUser, "");
            $obGlosas->ActualiceEstados($idFactura, $TipoArchivo, $idActividad, "");
            
            $ID=$DatosGlosa["ID"];
            $obGlosas->update("salud_conciliaciones_masivas_temp", "Conciliada", 1, "WHERE ID='$ID'");
            
            $sql="SELECT COUNT(*) as Total FROM salud_conciliaciones_masivas_temp WHERE Conciliada=1";
            $Datos=$obGlosas->Query($sql);
            $Datos=$obGlosas->FetchArray($Datos);
            $TotalGlosasRegistradas=$Datos["Total"];
            $Porcentaje=round((40/$TotalGlosas)*$TotalGlosasRegistradas);
            if($Porcentaje==40){
                $obGlosas->VaciarTabla("salud_conciliaciones_masivas_temp");
                print("FIN;");
            }else{
                print("OK;$TotalGlosas;$TotalGlosasRegistradas;$Porcentaje");
            }
            
        break;
        
        
    }
          
}else{
    print("No se enviaron parametros");
}
?>