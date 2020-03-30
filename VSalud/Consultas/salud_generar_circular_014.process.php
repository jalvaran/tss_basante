<?php
session_start();
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
include_once '../../modelo/php_conexion.php';
include_once '../clases/Circular014.class.php';
include_once("../css_construct.php");
/* 
 * Este archivo procesa la anulacion de un abono a un titulo
 */

$css =  new CssIni("id");
if(isset($_REQUEST["CmbAnio"])){
    
    $ob014=new Circular014($idUser); 
    
    $Anio=$ob014->normalizar($_REQUEST["CmbAnio"]);
    $Mes=$ob014->normalizar($_REQUEST["CmbMes"]);
              
    $NombreArchivo=$ob014->CrearCircular014($Mes, $Anio, "");
    print("<a href='ArchivosTemporales/$NombreArchivo' download><img src='../images/download.gif'></img></a>");
    //$css->CrearNotificacionNaranja("<a href='ArchivosTemporales/$NombreArchivo' download>Descargar</a>",16);
    
}else{
    $css->CrearNotificacionRoja("No se recibieron los parametros para 014",16);
}
?>