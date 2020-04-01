<?php

include_once("../../../modelo/php_conexion.php");
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

$sql = "SELECT * FROM salud_cie10 WHERE codigo_sistema='$key' or descripcion_cups like '%$key%' LIMIT 100"; 

$result = $obRest->Query($sql);

$json = [];

while($row = $obRest->FetchAssoc($result)){
    
    $Texto=$row['codigo_sistema']." || ". utf8_encode($row['descripcion_cups']);
    $json[] = ['id'=>$row['codigo_sistema'], 'text'=>$Texto];
}
echo json_encode($json);