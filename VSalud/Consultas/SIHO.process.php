<?php
session_start();
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
include_once '../../modelo/php_conexion.php';
include_once '../clases/SIHO.class.php';

if(isset($_REQUEST["TxtFechaCorte"])){
    
    $obSiho=new SIHO($idUser);     
    $FechaCorte=$obSiho->normalizar($_REQUEST["TxtFechaCorte"]);              
    $DatosCartera=$obSiho->CrearSIHO($FechaCorte,';',  "");
    
    echo json_encode($DatosCartera);
    
}else{
    $Respuesta["Error"]="No se recibieron parametros";
    echo json_encode($Respuesta);
}
?>