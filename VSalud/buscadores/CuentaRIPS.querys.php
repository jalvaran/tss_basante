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

$sql = "SELECT * FROM vista_salud_cuentas_rips 
		WHERE (CuentaRIPS LIKE '%$key%' or cod_enti_administradora LIKE '%$key%' OR  nom_enti_administradora "
        . "     LIKE '%$key%' or numero_radicado like '%$key%' or NombreCortoEPS like '%$key%')
		LIMIT 500"; 
$result = $obRest->Query($sql);
$json = [];

while($row = $obRest->FetchAssoc($result)){
    $Texto=$row['CuentaRIPS']." ".$row['NombreCortoEPS']." ".$row['cod_enti_administradora']." ".$row['nom_enti_administradora'];
     $json[] = ['id'=>$row['CuentaRIPS'], 'text'=>$Texto];
}
echo json_encode($json);