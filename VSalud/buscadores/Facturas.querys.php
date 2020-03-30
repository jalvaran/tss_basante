<?php
include_once("../../modelo/php_conexion.php");
session_start();
$idUser=$_SESSION['idUser'];
if($idUser==''){
    $json[0]['id']="";
    $json[0]['text']="Debe iniciar sesion para realizar la busqueda";
    echo json_encode($json);
    exit();
}
$obRest=new conexion($idUser);
$key=$obRest->normalizar($_REQUEST['q']);

$sql = "SELECT num_factura,nom_enti_administradora,CuentaRIPS,round(valor_neto_pagar) as Total,EstadoGlosa,eg.Estado_glosa "
        . " FROM salud_archivo_facturacion_mov_generados af "
        . "INNER JOIN salud_estado_glosas eg ON af.EstadoGlosa=eg.ID "
        . " WHERE af.num_factura LIKE '%$key%' OR af.nom_enti_administradora "
        . "LIKE '%$key%' OR af.CuentaRIPS like '%$key%' OR eg.Estado_glosa like '%$key%' LIMIT 500 "; 
		
$result = $obRest->Query($sql);
$json = [];

while($row = $obRest->FetchAssoc($result)){
    $Texto=$row['num_factura']." ".$row['nom_enti_administradora']." ".$row['CuentaRIPS']." $".number_format($row['Total'])." ".$row['Estado_glosa'];
     $json[] = ['id'=>$row['num_factura'], 'text'=>$Texto];
}
echo json_encode($json);