<?php
session_start();
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
include_once '../../modelo/php_conexion.php';
include_once '../clases/Circular030.class.php';
include_once("../css_construct.php");
/* 
 * Este archivo procesa la anulacion de un abono a un titulo
 */

$css =  new CssIni("id");
if(isset($_REQUEST["TxtFechaInicial"])){
    
    $ob030=new Circular030($idUser); 
    
    $FechaInicial=$ob030->normalizar($_REQUEST["TxtFechaInicial"]);
    $FechaFinal=$ob030->normalizar($_REQUEST["TxtFechaFinal"]);
    $Tipo=$ob030->normalizar($_REQUEST["CmbAdicional"]);
          
    $NombreArchivo=$ob030->CrearCircular030($FechaInicial, $FechaFinal, $Tipo, "");
    print("<a href='ArchivosTemporales/$NombreArchivo' download><img src='../images/download.gif'></img></a>");
    //$css->CrearNotificacionNaranja("<a href='ArchivosTemporales/$NombreArchivo' download>Descargar</a>",16);
    
}else{
    $css->CrearNotificacionRoja("No se recibieron los parametros",16);
}
?>