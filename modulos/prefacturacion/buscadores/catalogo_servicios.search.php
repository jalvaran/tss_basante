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

$sql = "SELECT * FROM catalogo_servicios WHERE CUPS = '$key' or Descripcion like '%$key%' LIMIT 100"; 

$result = $obRest->Query($sql);

$json = [];

while($row = $obRest->FetchAssoc($result)){
    
    $Texto= utf8_encode($row['CUPS']." || ".$row['Descripcion']);
    $json[] = ['id'=>$row['CUPS'], 'text'=>$Texto];
}
echo json_encode($json);