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

$sql = "SELECT t1.*,
        (SELECT CONCAT(t2.Nombre,' ',t2.Departamento) FROM catalogo_municipios t2 WHERE t1.CodigoDANE=t2.CodigoDANE LIMIT 1 ) as Municipio 
         FROM prefactura_paciente t1 WHERE t1.ID='$key' or t1.NumeroDocumento='$key' or t1.PrimerNombre like '%$key%' or t1.SegundoNombre like '%$key%' or t1.PrimerApellido like '%$key%' or t1.SegundoApellido like '%$key%' or t1.Telefono like '%$key%' LIMIT 100"; 

$result = $obRest->Query($sql);

$json = [];

while($row = $obRest->FetchAssoc($result)){
    
    $Texto=$row['ID']." || ". utf8_encode($row['PrimerNombre']." ".$row['SegundoNombre']." ".$row['PrimerApellido']." ".$row['SegundoApellido'])." ".$row['TipoDocumento']." || ".$row['NumeroDocumento']." || ".utf8_encode($row["Direccion"]." || ".$row["Telefono"]." || ".utf8_encode($row["Municipio"]));
    $json[] = ['id'=>$row['ID'], 'text'=>$Texto];
}
echo json_encode($json);