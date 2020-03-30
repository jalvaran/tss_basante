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
            if(!empty($_FILES['UpCargaMasivaGlosas']['name'])){
                
                $info = new SplFileInfo($_FILES['UpCargaMasivaGlosas']['name']);
                $Extension=($info->getExtension());
                $Atras="../";
                $carpeta="SoportesSalud/CargasMasivasGlosas/";
                opendir($Atras.$Atras.$carpeta);
                $Name=str_replace(' ','_','CargaGlosas_'.$Fecha."_".$_FILES['UpCargaMasivaGlosas']['name']);
                $destino=$carpeta.$Name;
                move_uploaded_file($_FILES['UpCargaMasivaGlosas']['tmp_name'],$Atras.$Atras.$destino);
            }
            $obGlosas->RegistreArchivoSubido($Fecha, $destino,$Extension, $idUser);
            print("OK");
        break;
        case 2://Borra la carga del ultimo archivo en caso de errores
            $sql="DELETE FROM salud_control_glosas_masivas WHERE Analizado=0";
            $obGlosas->Query($sql);
            $obGlosas->VaciarTabla("salud_glosas_masivas_temp");
            print("Temporales borrados");
        break; 
        case 3://Leer Archivo y llevarlo a la tabla temporal            
            $obGlosas->LeerArchivo("");
            print("OK");
        break; 
        case 4://Se realizan las validaciones
            $Parametros=$obGlosas->DevuelveValores("salud_parametros_generales", "ID", 1);
            $Errores=0;
            $sql="UPDATE salud_glosas_masivas_temp t1 INNER JOIN salud_archivo_facturacion_mov_generados t2 ON t1.num_factura=t2.num_factura 
                    SET t1.CuentaRIPS=t2.CuentaRIPS;";
            $obGlosas->Query($sql);
            $sql="SELECT * FROM vista_salud_glosas_masivas";
            $Datos=$obGlosas->Query($sql);
            while($DatosCarga=$obGlosas->FetchArray($Datos)){
                if($DatosCarga["Factura"]==''){ //Si existe la factura en los AF
                    $css->CrearNotificacionRoja("<br>Error: la factura No existe en los registros AF, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                if($DatosCarga["CuentaRIPS"]==''){ //Si existe la factura en los AF
                    $css->CrearNotificacionRoja("<br>Error: la Factura no está asociada a la CuentaRIPS, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                if(($DatosCarga["CodEps"]=='')){ //Si la EPS no está asociada a la factura
                    $css->CrearNotificacionRoja("<br>Error: la Factura no está asociada a la EPS relacionada, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                if(($DatosCarga["NIT"]=='')){ //Si la EPS no está asociada a la factura
                    $css->CrearNotificacionRoja("<br>Error: El NIT No coincide con la EPS relacionada, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                if(($DatosCarga["CodigoGlosa"]=='')){ //Si la EPS no está asociada a la factura
                    $css->CrearNotificacionRoja("<br>Error: El Codigo de la glosa no existe, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                if(($DatosCarga["CodigoActividadAM"]=='') AND ($DatosCarga["CodigoActividadAT"]=='') AND ($DatosCarga["CodigoActividadAP"]=='') AND ($DatosCarga["CodigoActividadAC"]=='') ){ //Si la actividad no existe en ninguno de los archivos
                    $css->CrearNotificacionRoja("<br>Error: La factura no contiene el codigo de la actividad relacionada, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                $TotalActividad=$DatosCarga["TotalAM"]+$DatosCarga["TotalAP"]+$DatosCarga["TotalAC"]+$DatosCarga["TotalAT"];
                if($DatosCarga["ValorGlosado"]>$TotalActividad){
                    $css->CrearNotificacionRoja("<br>Error: El Valor Glosado No puede ser superior al total de la actividad, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
               
                }
                if($DatosCarga["ValorGlosado"]<0){
                    $css->CrearNotificacionRoja("<br>Error: El Valor Glosado No puede negativo, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
               
                }
                if(($DatosCarga["idGlosa"]>0)){ //Si la EPS no está asociada a la factura
                    $css->CrearNotificacionRoja("<br>Error: Ya se registró una Glosa con el código relacionado para la factura y actividad, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                if(($DatosCarga["idGlosaTemp"]>0)){ //Si la EPS no está asociada a la factura
                    $css->CrearNotificacionRoja("<br>Error: Existe una Glosa con el código relacionado para la factura y actividad a la espera de guardarse, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                
                $Dias=$obGlosas->CalculeDiferenciaFechas($DatosCarga["FechaIPS"], date("Y-m-d"), "");
                if($Dias["Dias"]>$Parametros["Valor"]){
                    $css->CrearNotificacionRoja("<br>Error: La Factura Superó el numero de días ($Dias[Dias] de un máximo posible de $Parametros[Valor]) para realizar glosas, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
                $idFactura=$DatosCarga["Factura"];
                $idActividad=$DatosCarga["CodigoActividad"];
                $TotalGlosasExistentes=$obGlosas->Sume("salud_glosas_iniciales", "ValorGlosado", " WHERE num_factura='$idFactura' AND CodigoActividad='$idActividad' AND (EstadoGlosa<>13 AND EstadoGlosa<>12)");
            
                $TotalPorGlosar=$obGlosas->Sume("salud_glosas_masivas_temp", "ValorGlosado", " WHERE num_factura='$idFactura' AND CodigoActividad='$idActividad' AND GlosaInicial=0");
                $GlosasTotales=$TotalPorGlosar+$TotalGlosasExistentes;
                
                $ValorActividad=$DatosCarga["TotalAM"]+$DatosCarga["TotalAT"]+$DatosCarga["TotalAP"]+$DatosCarga["TotalAC"];
                
                if($GlosasTotales>$ValorActividad){
                    $css->CrearNotificacionRoja("<br>Error: El Valor Total A Glosar Excede al Valor de la actividad, Linea: ".$DatosCarga["ID"],14);
                    $Errores=$Errores+1;
                }
            }
            
            $sql="SELECT COUNT(*) as Repetidos FROM salud_glosas_masivas_temp GROUP BY num_factura,CodigoActividad,CodigoGlosa HAVING COUNT(*) > 1 LIMIT 1";
            $Datos=$obGlosas->Query($sql);
            while($DatosCarga=$obGlosas->FetchArray($Datos)){
                if($DatosCarga["Repetidos"]>1){
                    $css->CrearNotificacionRoja("<br>Error: El Archivo Subido contiene Registros donde se repiten el Numero de factura, Codigo de Actividad y Codigo de Glosa.",14);
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
        case 5://Crea las glosas iniciales a partir de la tabla temporal de glosas masivas
            $sql="SELECT COUNT(*) as Total FROM salud_glosas_masivas_temp";
            $Datos=$obGlosas->Query($sql);
            $Datos=$obGlosas->FetchArray($Datos);
            $TotalGlosas=$Datos["Total"];
            
            $Datos=$obGlosas->ConsultarTabla("vista_salud_glosas_masivas", "WHERE GlosaInicial=0 LIMIT 1");
            $DatosGlosa=$obGlosas->FetchArray($Datos);
            $ValorActividad=$DatosGlosa["TotalAM"]+$DatosGlosa["TotalAT"]+$DatosGlosa["TotalAP"]+$DatosGlosa["TotalAC"];
            $TipoArchivo="";
            $NombreActividad='';
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
                       
            
            $idGlosa=$obGlosas->RegistrarGlosaInicial($DatosGlosa["Factura"], $DatosGlosa["CodigoActividad"], $ValorActividad, $DatosGlosa["FechaIPS"], $DatosGlosa["FechaAuditoria"], $DatosGlosa["CodigoGlosa"], $DatosGlosa["ValorGlosado"], 0, $DatosGlosa["ValorGlosado"], "");
            $obGlosas->RegistraGlosaRespuesta($TipoArchivo, $idGlosa, $DatosGlosa["Factura"], $DatosGlosa["CodigoActividad"], $NombreActividad, $ValorActividad, 1, $DatosGlosa["FechaIPS"], $DatosGlosa["FechaAuditoria"], $DatosGlosa["Observaciones"], $DatosGlosa["CodigoGlosa"], $DatosGlosa["ValorGlosado"], 0, 0, $DatosGlosa["ValorGlosado"], $DatosGlosa["Soporte"], $idUser, "");
            $obGlosas->ActualiceEstados($DatosGlosa["Factura"], $TipoArchivo, $DatosGlosa["CodigoActividad"], "");
            $ID=$DatosGlosa["ID"];
            $obGlosas->update("salud_glosas_masivas_temp", "GlosaInicial", 1, "WHERE ID='$ID'");
            
            $sql="SELECT COUNT(*) as Total FROM salud_glosas_masivas_temp WHERE GlosaInicial=1";
            $Datos=$obGlosas->Query($sql);
            $Datos=$obGlosas->FetchArray($Datos);
            $TotalGlosasRegistradas=$Datos["Total"];
            if($TotalGlosas==0){
                $TotalGlosas=1;
            }
            $Porcentaje=round((40/$TotalGlosas)*$TotalGlosasRegistradas);
            
            if($Porcentaje==40){
                $obGlosas->VaciarTabla("salud_glosas_masivas_temp");
                
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