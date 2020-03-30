<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
include_once("../../modelo/php_conexion.php");
include_once("../clases/SaludRips.class.php");

if(!empty($_REQUEST["idValidacion"])){
    
    $obCon = new conexion($idUser);
    $idValidacion=$obCon->normalizar($_REQUEST["idValidacion"]);
    switch ($idValidacion){
        case 1: //Verifique si una cuenta RIPS Existe
            $DatosCuentaRIPS["msg"]="OK";
            $obValidacion= new Rips($idUser);
            $CuentaRIPS=$obCon->normalizar($_REQUEST["CuentaRIPS"]);
            $CuentaRIPS=str_pad($CuentaRIPS, 6, "0", STR_PAD_LEFT);
            $sql="SELECT CuentaRIPS FROM salud_archivo_facturacion_mov_generados WHERE CuentaRIPS='$CuentaRIPS' LIMIT 1";
            $consulta=$obCon->Query($sql);
            $DatosConsulta=$obCon->FetchArray($consulta);
            if($DatosConsulta["CuentaRIPS"]==$CuentaRIPS){
                $DatosCuentaRIPS["msg"]="Error";
            }
                        
            echo json_encode($DatosCuentaRIPS);
        break;
        case 2: //Verifique si una hay facturas duplicadas en estado de devolucion
            $Mensaje["msg"]="NO";
            $obValidacion= new Rips($idUser);
            
            $sql="SELECT ID FROM vista_af_devueltos LIMIT 1";
            $consulta=$obCon->Query($sql);
            $DatosConsulta=$obCon->FetchArray($consulta);
            if($DatosConsulta["ID"]>0){
                $Mensaje["msg"]="SI";
            }
                        
            echo json_encode($Mensaje);
        break;
        case 3: //Se actualiza las facturas devueltas ingresando el nuevo cargue al archivo AF y guardando el antiguo a una tabla de historicos
            $Mensaje["msg"]="OK";
            $obValidacion= new Rips($idUser);
            $obValidacion->ActualiceAFDevueltas("");
            echo json_encode($Mensaje);
        break;
    }
    
}
?>