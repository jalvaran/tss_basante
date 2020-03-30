<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
include_once("../../modelo/php_conexion.php");
include_once("../css_construct.php");
include_once("../clases/SaludReportes.class.php");


$obCon = new Reportes($idUser);
if($_REQUEST["idAccion"]){
    $css =  new CssIni("id",0);
    
    switch ($_REQUEST["idAccion"]){
        case 1://Se reciben las cuentas y se llevan las facturas con respuestas a la tabla para iniciar el proceso de consulta de respuestas y regostro en el excel
                        
            $CuentaRIPS=explode(",",$obCon->normalizar($_REQUEST["Cuentas"]));
           
            foreach ($CuentaRIPS as $key){
                $sql="SELECT num_factura,"
                        . " (SELECT cod_enti_administradora FROM salud_archivo_facturacion_mov_generados"
                        . " WHERE salud_archivo_facturacion_mov_generados.num_factura=salud_archivo_control_glosas_respuestas.num_factura) as idEPS"
                        . " FROM salud_archivo_control_glosas_respuestas WHERE CuentaRIPS='$key' and (EstadoGlosa='2' or EstadoGlosa='4') GROUP BY num_factura";
                $consulta=$obCon->Query($sql);
                while($DatosFacturas=$obCon->FetchArray($consulta)){
                    $obCon->CargaFacturasAResponder($key,$DatosFacturas["num_factura"], $DatosFacturas["idEPS"], "");
                }
               
            }
            $sql="SELECT idEPS FROM salud_control_generacion_respuestas_excel GROUP BY idEPS";
            $consulta=$obCon->Query($sql);
            
            if($obCon->NumRows($consulta)==1){
                print("OK");
            }else if($obCon->NumRows($consulta)==0){
                print("<h4 style='color:orange'>No se encontraron respuestas para las facturas asociadas a las cuentas seleccionadas</h4>");
            }else{
                print("<h4 style='color:red'>Se seleccionaron cuentas de dos o mas EPS, Sólo es posible seleccionar cuentas de una EPS</h4>");
           
            }
            
        break;
        
        case 2://Se borra el control de respuestas en caso de que ocurra un error
            $obCon->VaciarTabla("salud_control_generacion_respuestas_excel");
            $obCon->BorrarCarga();
            print("OK");
        break;
    
        case 3://Crear el archivo y Carpeta
            $obCon->CrearArchivoRespuestas("Respuestas.xlsx", 0, "");
            print("OK");
        break;
        
        case 4://Crear las respuestas para cada Factura
                 
            $NombreArchivo="Respuestas.xlsx";
            $obCon->RegistreRespuestasFacturaExcel($NombreArchivo,"");
            print("OK");
            
        break;
        case 5://Crear las respuestas para cada Factura
            $obCon->PrepareSoportes();
            print("OK");
        break; 
        case 6://Comprimir Archivos
            $Soportes=$obCon->normalizar($_REQUEST["Soportes"]);
            $Fecha=date("Y-m-d");
            $sql="SELECT re.idEPS,se.cod_pagador_min FROM salud_control_generacion_respuestas_excel re "
                    . " INNER JOIN salud_eps se ON cod_pagador_min=idEPS"
                    . " WHERE Generada=1 LIMIT 1";                
            $consulta= $obCon->Query($sql);
            $Datos=$obCon->FetchAssoc($consulta);
            $idEPS=(htmlentities($Datos["cod_pagador_min"]));
            $idEPS=preg_replace('/\&(.)[^;]*;/', '\\1',$idEPS);
            $idEPS=str_replace(' ','_',$idEPS); 
            
            $NombreArchivo=$idEPS."_".$Fecha."_ReporteXCuentas.zip";
            $Respuesta=$obCon->ComprimaRespuesta($NombreArchivo,$Soportes);
            
            $imagerute="../images/dardebaja.png";
            $page="ArchivosTemporales/Reportes/$NombreArchivo";
            //$css->CrearLink($page, "_blank", "Descargar");
            //print("<a href='$page' target='_blank'>");
            //$css->CrearBotonEvento("BtnDescargar", "Descargar", 1, "", "", "naranja", "");
            //$css->CrearBoton("BtnDescargar", "Bajar");
            //print("</a>");
            $css->CrearImageLink($page, $imagerute, "_blank",70,70);
            $obCon->VaciarTabla("salud_control_generacion_respuestas_excel");
        break; 
        case 7://Se reciben las facturas y se llenan con respuestas a la tabla para iniciar el proceso de consulta de respuestas y registro en el excel
                        
            $Facturas=explode(",",$obCon->normalizar($_REQUEST["CmbFacturas"]));
           
            foreach ($Facturas as $key){
                $sql="SELECT num_factura,"
                        . " (SELECT cod_enti_administradora FROM salud_archivo_facturacion_mov_generados"
                        . " WHERE salud_archivo_facturacion_mov_generados.num_factura=salud_glosas_iniciales.num_factura) as idEPS"
                        . " FROM salud_glosas_iniciales WHERE num_factura='$key' and (EstadoGlosa='2' or EstadoGlosa='4') GROUP BY num_factura";
                $consulta=$obCon->Query($sql);
                while($DatosFacturas=$obCon->FetchArray($consulta)){
                    $obCon->CargaFacturasAResponder("",$DatosFacturas["num_factura"], $DatosFacturas["idEPS"], "");
                }
               
            }
            $sql="SELECT idEPS FROM salud_control_generacion_respuestas_excel GROUP BY idEPS";
            $consulta=$obCon->Query($sql);
            
            if($obCon->NumRows($consulta)==1){
                print("OK");
            }else if($obCon->NumRows($consulta)==0){
                print("<h4 style='color:orange'>No se encontraron respuestas para las facturas asociadas a las cuentas seleccionadas</h4>");
            }else{
                print("<h4 style='color:red'>Se seleccionaron cuentas de dos o mas EPS, Sólo es posible seleccionar cuentas de una EPS</h4>");
           
            }
            
        break;
        
        case 8://Se recibe un rango de fechas de facturas
            
            $FechaInicial=$obCon->normalizar($_REQUEST["FechaFacturaInicial"]);
            $FechaFinal=$obCon->normalizar($_REQUEST["FechaFacturaFinal"]);
            $idEPS=$obCon->normalizar($_REQUEST["idEPS"]);
                $sql="SELECT gi.num_factura,af.cod_enti_administradora as idEPS,af.CuentaRIPS"
                        
                        . " FROM salud_glosas_iniciales gi INNER JOIN salud_archivo_facturacion_mov_generados af "
                        . "ON gi.num_factura=af.num_factura WHERE (gi.EstadoGlosa='2' or gi.EstadoGlosa='4')"
                        . " AND af.fecha_factura>='$FechaInicial' "
                        . " AND af.fecha_factura<='$FechaFinal' AND af.cod_enti_administradora='$idEPS' GROUP BY gi.num_factura";
                $consulta=$obCon->Query($sql);
                while($DatosFacturas=$obCon->FetchArray($consulta)){
                    $obCon->CargaFacturasAResponder("",$DatosFacturas["num_factura"], $DatosFacturas["idEPS"], "");
                }
               
            
            $sql="SELECT idEPS FROM salud_control_generacion_respuestas_excel GROUP BY idEPS";
            $consulta=$obCon->Query($sql);
            
            if($obCon->NumRows($consulta)==1){
                print("OK");
            }else if($obCon->NumRows($consulta)==0){
                print("<h4 style='color:orange'>No se encontraron respuestas para las facturas asociadas a las cuentas seleccionadas</h4>");
            }else{
                print("<h4 style='color:red'>Se seleccionaron cuentas de dos o mas EPS, Sólo es posible seleccionar cuentas de una EPS</h4>");
           
            }
            
        break;
        
        case 9://Se recibe un rango de fechas de radicado
            
            $FechaInicial=$obCon->normalizar($_REQUEST["FechaRadicadoInicial"]);
            $FechaFinal=$obCon->normalizar($_REQUEST["FechaRadicadoFinal"]);
            $idEPS=$obCon->normalizar($_REQUEST["idEPS"]);
                $sql="SELECT gi.num_factura,af.cod_enti_administradora as idEPS,af.CuentaRIPS"
                        
                        . " FROM salud_glosas_iniciales gi INNER JOIN salud_archivo_facturacion_mov_generados af "
                        . "ON gi.num_factura=af.num_factura WHERE (gi.EstadoGlosa='2' or gi.EstadoGlosa='4')"
                        . " AND af.fecha_radicado>='$FechaInicial' "
                        . " AND af.fecha_radicado<='$FechaFinal' AND af.cod_enti_administradora='$idEPS' GROUP BY gi.num_factura";
                $consulta=$obCon->Query($sql);
                while($DatosFacturas=$obCon->FetchArray($consulta)){
                    $obCon->CargaFacturasAResponder("",$DatosFacturas["num_factura"], $DatosFacturas["idEPS"], "");
                }
               
            
            $sql="SELECT idEPS FROM salud_control_generacion_respuestas_excel GROUP BY idEPS";
            $consulta=$obCon->Query($sql);
            
            if($obCon->NumRows($consulta)==1){
                print("OK");
            }else if($obCon->NumRows($consulta)==0){
                print("<h4 style='color:orange'>No se encontraron respuestas para las facturas asociadas a las cuentas seleccionadas</h4>");
            }else{
                print("<h4 style='color:red'>Se seleccionaron cuentas de dos o mas EPS, Sólo es posible seleccionar cuentas de una EPS</h4>");
           
            }
            
        break;
    }
    
}else{
    
    print("No se recibieron parametros");
}
    
    

?>