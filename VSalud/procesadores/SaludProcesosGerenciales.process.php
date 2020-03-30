<?php 
include_once("clases/SaludProcesosGerenciales.class.php");

$obProceso=new ProcesoGerencial($idUser);
// para proccesos nuevos
if(isset($_REQUEST["BtnGuardar"])){
    
    $Fecha=$obProceso->normalizar($_REQUEST["TxtFecha"]);
    $Nombre=$obProceso->normalizar($_REQUEST["TxtNombre"]);
    $idConcepto=$obProceso->normalizar($_REQUEST["CmbConcepto"]);
    $idEps=$obProceso->normalizar($_REQUEST["CmbEps"]);
    $idIps=$obProceso->normalizar($_REQUEST["CmbIps"]);
    $Observaciones=$obProceso->normalizar($_REQUEST["TxtObservaciones"]);
    $obProceso->CrearProcesoGerencial($Fecha,$idIps,$idEps, $Nombre, $idConcepto, $Observaciones, $idUser, "");
    header("location:$myPage");
    
}
// para agregar soporte a un proceso
if(isset($_REQUEST["BtnAgregarSoporte"])){
    
    $Fecha=$obProceso->normalizar($_REQUEST["TxtFecha"]);
    
    $idProceso=$obProceso->normalizar($_REQUEST["idProceso"]);
    $Observaciones=$obProceso->normalizar($_REQUEST["TxtObservaciones"]);
    $obProceso->AgregarSoporteProcesoGerencial($Fecha, $idProceso, $Observaciones, $Vector);
    
    header("location:$myPage");
    
}


///////////////fin
?>