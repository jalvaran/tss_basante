<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
include_once("../../modelo/php_conexion.php");
include_once("../css_construct.php");
include_once("../clases/Circular030.class.php");

if($_REQUEST["idAccion"]){
    $css =  new CssIni("id",0);
    $obCon = new Circular030($idUser);
    switch ($_REQUEST["idAccion"]){
        case 1: //Escribe los radicados en periodo
                        
            $TxtFechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]);
            $CmbAdicional=$obCon->normalizar($_REQUEST["CmbAdicional"]);
            $Contador=$obCon->normalizar($_REQUEST["Contador"]);
            $TotalRegistros=$obCon->normalizar($_REQUEST["TotalRegistros"]);
            $NombreArchivo=$obCon->normalizar($_REQUEST["NombreArchivo"]);
            if($TotalRegistros==''){
                $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_radicados_periodo ";
                        
                $consulta=$obCon->Query($sql);
                $DatosRegistros=$obCon->FetchAssoc($consulta);
                $TotalRegistros=$DatosRegistros["TotalRegistros"];
            }
            $TotalRegistrosRealizados=$obCon->Escribir030_Radicados_Rango($TxtFechaInicial, $TxtFechaFinal, $Contador, $CmbAdicional,$NombreArchivo, "");
            $Fin="";
            if($TotalRegistrosRealizados==$TotalRegistros){
                $Fin="Fin";
            }
            print("OK;$TotalRegistros;$TotalRegistrosRealizados;$Fin");
            
        break;
        case 2:// registro los juridicos en periodo
                        
            $TxtFechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]);
            $CmbAdicional=$obCon->normalizar($_REQUEST["CmbAdicional"]);
            $Contador=$obCon->normalizar($_REQUEST["Contador"]);
            $TotalRegistros=$obCon->normalizar($_REQUEST["TotalRegistros"]);
            $ContadorGeneral=$obCon->normalizar($_REQUEST["ContadorGeneral"]);
            $NombreArchivo=$obCon->normalizar($_REQUEST["NombreArchivo"]);
            if($ContadorGeneral==''){
                $ContadorGeneral=0;
            }
            if($TotalRegistros==''){
                $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_juridicos_periodo ";
                $consulta=$obCon->Query($sql);
                $DatosRegistros=$obCon->FetchAssoc($consulta);
                $TotalRegistros=$DatosRegistros["TotalRegistros"];
            }
            $Contadores=$obCon->Escribir030_Juridicos_Rango($TxtFechaInicial, $TxtFechaFinal, $Contador, $CmbAdicional,$ContadorGeneral,$NombreArchivo, "");
            $TotalRegistrosRealizados=$Contadores[0];
            $Fin="";
            if($TotalRegistrosRealizados==$TotalRegistros){
                $Fin="Fin";
            }
            $ContadorGeneral=$Contadores[1];
            print("OK;$TotalRegistros;$TotalRegistrosRealizados;$Fin;$ContadorGeneral");
            
        break;//Fin caso 2
        case 3://radicados anteriores
                        
            $TxtFechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]);
            $CmbAdicional=$obCon->normalizar($_REQUEST["CmbAdicional"]);
            $Contador=$obCon->normalizar($_REQUEST["Contador"]);
            $TotalRegistros=$obCon->normalizar($_REQUEST["TotalRegistros"]);
            $ContadorGeneral=$obCon->normalizar($_REQUEST["ContadorGeneral"]);
            
            if($TotalRegistros==''){
                $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_1_radicados "
                        . "WHERE FechaPresentacion<'$TxtFechaInicial'";
                $consulta=$obCon->Query($sql);
                $DatosRegistros=$obCon->FetchAssoc($consulta);
                $TotalRegistros=$DatosRegistros["TotalRegistros"];
            }
            $Contadores=$obCon->Escribir030_Radicados_Iniciales($TxtFechaInicial, $TxtFechaFinal, $Contador, $CmbAdicional,$ContadorGeneral, "");
            $TotalRegistrosRealizados=$Contadores[0];
            $Fin="";
            if($TotalRegistrosRealizados==$TotalRegistros){
                $Fin="Fin";
            }
            $ContadorGeneral=$Contadores[1];
            print("OK;$TotalRegistros;$TotalRegistrosRealizados;$Fin;$ContadorGeneral");
            
        break;//fin caso 3
        
        case 4://Construye el registro de control
                        
            $TxtFechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]);                      
            $ContadorGeneral=$obCon->normalizar($_REQUEST["GranTotalRegistros"]);            
            $NombreArchivo=$obCon->ConstruirEncabezado($TxtFechaInicial, $TxtFechaFinal,$ContadorGeneral, "");
            
            print("OK;$NombreArchivo");
            
        break;//fin caso 4
    
        case 5://Construye las vistas necesarias
                        
            $TxtFechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]); 
            $CmbAdicional=$obCon->normalizar($_REQUEST["CmbAdicional"]);
            $obCon->ConstruyaVista030($TxtFechaInicial, $TxtFechaFinal, $CmbAdicional, "");
            print("OK");
            
        break;//fin caso 5
    
        case 6://Calcula el total de registros a consultar
                        
            $TxtFechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]); 
            $CmbAdicional=$obCon->normalizar($_REQUEST["CmbAdicional"]);
            
            $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_radicados_periodo ";                        
            $consulta=$obCon->Query($sql);
            $DatosRegistros=$obCon->FetchAssoc($consulta);
            $TotalRegistros=$DatosRegistros["TotalRegistros"];
            
            $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_juridicos_periodo ";                        
            $consulta=$obCon->Query($sql);
            $DatosRegistros=$obCon->FetchAssoc($consulta);
            $TotalRegistros=$TotalRegistros+$DatosRegistros["TotalRegistros"];
            
            $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_pagadas_periodo ";                        
            $consulta=$obCon->Query($sql);
            $DatosRegistros=$obCon->FetchAssoc($consulta);
            $TotalRegistros=$TotalRegistros+$DatosRegistros["TotalRegistros"];
            
            $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_diferencia_periodo ";                        
            $consulta=$obCon->Query($sql);
            $DatosRegistros=$obCon->FetchAssoc($consulta);
            $TotalRegistros=$TotalRegistros+$DatosRegistros["TotalRegistros"];
            
            $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_pagadas_en_periodo_fuera_rango ";                        
            $consulta=$obCon->Query($sql);
            $DatosRegistros=$obCon->FetchAssoc($consulta);
            $TotalRegistros=$TotalRegistros+$DatosRegistros["TotalRegistros"];
            
            if($CmbAdicional=='2'){
            
                $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_pagadas_anteriores ";                        
                $consulta=$obCon->Query($sql);
                $DatosRegistros=$obCon->FetchAssoc($consulta);
                $TotalRegistros=$TotalRegistros+$DatosRegistros["TotalRegistros"];

                $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_radicados_anteriores ";                        
                $consulta=$obCon->Query($sql);
                $DatosRegistros=$obCon->FetchAssoc($consulta);
                $TotalRegistros=$TotalRegistros+$DatosRegistros["TotalRegistros"];

                $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_diferencia_anteriores ";                        
                $consulta=$obCon->Query($sql);
                $DatosRegistros=$obCon->FetchAssoc($consulta);
                $TotalRegistros=$TotalRegistros+$DatosRegistros["TotalRegistros"];
            }
            ///Faltan los juridicos anteriores y todo lo que tiene que ver con glosas
            print("OK;$TotalRegistros");
            
        break;//fin caso 6
        
        case 7:// registro las facturas que fueron pagadas dentro del rango
                        
            $TxtFechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]);
            $CmbAdicional=$obCon->normalizar($_REQUEST["CmbAdicional"]);
            $Contador=$obCon->normalizar($_REQUEST["Contador"]);
            $TotalRegistros=$obCon->normalizar($_REQUEST["TotalRegistros"]);
            $ContadorGeneral=$obCon->normalizar($_REQUEST["ContadorGeneral"]);
            $NombreArchivo=$obCon->normalizar($_REQUEST["NombreArchivo"]);
            if($ContadorGeneral==''){
                $ContadorGeneral=0;
            }
            if($TotalRegistros==''){
                $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_pagadas_periodo ";
                $consulta=$obCon->Query($sql);
                $DatosRegistros=$obCon->FetchAssoc($consulta);
                $TotalRegistros=$DatosRegistros["TotalRegistros"];
            }
            $Contadores=$obCon->Escribir030_Pagadas_Rango($TxtFechaInicial, $TxtFechaFinal, $Contador, $CmbAdicional,$ContadorGeneral,$NombreArchivo, "");
            $TotalRegistrosRealizados=$Contadores[0];
            $Fin="";
            if($TotalRegistrosRealizados==$TotalRegistros){
                $Fin="Fin";
            }
            $ContadorGeneral=$Contadores[1];
            print("OK;$TotalRegistros;$TotalRegistrosRealizados;$Fin;$ContadorGeneral");
            
        break;//Fin caso 7
        
        case 8:// registro las facturas que fueron pagadas anteriores
                        
            $TxtFechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]);
            $CmbAdicional=$obCon->normalizar($_REQUEST["CmbAdicional"]);
            $Contador=$obCon->normalizar($_REQUEST["Contador"]);
            $TotalRegistros=$obCon->normalizar($_REQUEST["TotalRegistros"]);
            $ContadorGeneral=$obCon->normalizar($_REQUEST["ContadorGeneral"]);
            $NombreArchivo=$obCon->normalizar($_REQUEST["NombreArchivo"]);
            if($ContadorGeneral==''){
                $ContadorGeneral=0;
            }
            if($TotalRegistros==''){
                $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_pagadas_anteriores ";
                $consulta=$obCon->Query($sql);
                $DatosRegistros=$obCon->FetchAssoc($consulta);
                $TotalRegistros=$DatosRegistros["TotalRegistros"];
            }
            $Contadores=$obCon->Escribir030_Pagadas_Anteriores($TxtFechaInicial, $TxtFechaFinal, $Contador, $CmbAdicional,$ContadorGeneral,$NombreArchivo, "");
            $TotalRegistrosRealizados=$Contadores[0];
            $Fin="";
            if($TotalRegistrosRealizados==$TotalRegistros){
                $Fin="Fin";
            }
            $ContadorGeneral=$Contadores[1];
            print("OK;$TotalRegistros;$TotalRegistrosRealizados;$Fin;$ContadorGeneral");
            
        break;//Fin caso 8
        
        case 9:// registro las facturas que fueron radicados antes del periodo seleccionado
                        
            $TxtFechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]);
            $CmbAdicional=$obCon->normalizar($_REQUEST["CmbAdicional"]);
            $Contador=$obCon->normalizar($_REQUEST["Contador"]);
            $TotalRegistros=$obCon->normalizar($_REQUEST["TotalRegistros"]);
            $ContadorGeneral=$obCon->normalizar($_REQUEST["ContadorGeneral"]);
            $NombreArchivo=$obCon->normalizar($_REQUEST["NombreArchivo"]);
            if($ContadorGeneral==''){
                $ContadorGeneral=0;
            }
            if($TotalRegistros==''){
                $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_radicados_anteriores ";
                $consulta=$obCon->Query($sql);
                $DatosRegistros=$obCon->FetchAssoc($consulta);
                $TotalRegistros=$DatosRegistros["TotalRegistros"];
            }
            $Contadores=$obCon->Escribir030_Radicados_Anteriores($TxtFechaInicial, $TxtFechaFinal, $Contador, $CmbAdicional,$ContadorGeneral,$NombreArchivo, "");
            $TotalRegistrosRealizados=$Contadores[0];
            $Fin="";
            if($TotalRegistrosRealizados==$TotalRegistros){
                $Fin="Fin";
            }
            $ContadorGeneral=$Contadores[1];
            print("OK;$TotalRegistros;$TotalRegistrosRealizados;$Fin;$ContadorGeneral");
            
        break;//Fin caso 9
        
        case 10:// registro las facturas que fueron pagadas con diferencia dentro del rango
                        
            $TxtFechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]);
            $CmbAdicional=$obCon->normalizar($_REQUEST["CmbAdicional"]);
            $Contador=$obCon->normalizar($_REQUEST["Contador"]);
            $TotalRegistros=$obCon->normalizar($_REQUEST["TotalRegistros"]);
            $ContadorGeneral=$obCon->normalizar($_REQUEST["ContadorGeneral"]);
            $NombreArchivo=$obCon->normalizar($_REQUEST["NombreArchivo"]);
            if($ContadorGeneral==''){
                $ContadorGeneral=0;
            }
            if($TotalRegistros==''){
                $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_diferencia_periodo ";
                $consulta=$obCon->Query($sql);
                $DatosRegistros=$obCon->FetchAssoc($consulta);
                $TotalRegistros=$DatosRegistros["TotalRegistros"];
            }
            $Contadores=$obCon->Escribir030_Diferencia_Rango($TxtFechaInicial, $TxtFechaFinal, $Contador, $CmbAdicional,$ContadorGeneral,$NombreArchivo, "");
            $TotalRegistrosRealizados=$Contadores[0];
            $Fin="";
            if($TotalRegistrosRealizados==$TotalRegistros){
                $Fin="Fin";
            }
            $ContadorGeneral=$Contadores[1];
            print("OK;$TotalRegistros;$TotalRegistrosRealizados;$Fin;$ContadorGeneral");
            
        break;//Fin caso 10
        
        case 11:// registro las facturas que fueron pagadas con diferencia anteriores
                        
            $TxtFechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]);
            $CmbAdicional=$obCon->normalizar($_REQUEST["CmbAdicional"]);
            $Contador=$obCon->normalizar($_REQUEST["Contador"]);
            $TotalRegistros=$obCon->normalizar($_REQUEST["TotalRegistros"]);
            $ContadorGeneral=$obCon->normalizar($_REQUEST["ContadorGeneral"]);
            $NombreArchivo=$obCon->normalizar($_REQUEST["NombreArchivo"]);
            if($ContadorGeneral==''){
                $ContadorGeneral=0;
            }
            if($TotalRegistros==''){
                $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_diferencia_anteriores ";
                $consulta=$obCon->Query($sql);
                $DatosRegistros=$obCon->FetchAssoc($consulta);
                $TotalRegistros=$DatosRegistros["TotalRegistros"];
            }
            $Contadores=$obCon->Escribir030_Diferencia_Anteriores($TxtFechaInicial, $TxtFechaFinal, $Contador, $CmbAdicional,$ContadorGeneral,$NombreArchivo, "");
            $TotalRegistrosRealizados=$Contadores[0];
            $Fin="";
            if($TotalRegistrosRealizados==$TotalRegistros){
                $Fin="Fin";
            }
            $ContadorGeneral=$Contadores[1];
            print("OK;$TotalRegistros;$TotalRegistrosRealizados;$Fin;$ContadorGeneral");
            
        break;//Fin caso 11
        
        case 12:// registro las facturas que fueron pagadas con diferencia anteriores
                        
            $TxtFechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]);
            $CmbAdicional=$obCon->normalizar($_REQUEST["CmbAdicional"]);
            $Contador=$obCon->normalizar($_REQUEST["Contador"]);
            $TotalRegistros=$obCon->normalizar($_REQUEST["TotalRegistros"]);
            $ContadorGeneral=$obCon->normalizar($_REQUEST["ContadorGeneral"]);
            $NombreArchivo=$obCon->normalizar($_REQUEST["NombreArchivo"]);
            if($ContadorGeneral==''){
                $ContadorGeneral=0;
            }
            if($TotalRegistros==''){
                $sql="SELECT COUNT(*) as TotalRegistros FROM vista_circular030_pagadas_en_periodo_fuera_rango ";
                $consulta=$obCon->Query($sql);
                $DatosRegistros=$obCon->FetchAssoc($consulta);
                $TotalRegistros=$DatosRegistros["TotalRegistros"];
            }
            $Contadores=$obCon->Escribir030_Pagadas_Dentro_Radicadas_Anteriores($TxtFechaInicial, $TxtFechaFinal, $Contador, $CmbAdicional,$ContadorGeneral,$NombreArchivo, "");
            $TotalRegistrosRealizados=$Contadores[0];
            $Fin="";
            if($TotalRegistrosRealizados==$TotalRegistros){
                $Fin="Fin";
            }
            $ContadorGeneral=$Contadores[1];
            print("OK;$TotalRegistros;$TotalRegistrosRealizados;$Fin;$ContadorGeneral");
            
        break;//Fin caso 12
        
    }
    
}else{
    
    print("No se recibieron parametros");
}
    
    

?>