<?php 
if(isset($_REQUEST["idDocumento"])){
    $myPage="GeneradorExcel.php";
    include_once("../../modelo/php_conexion.php");
    include_once("../../modelo/php_tablas.php");
    include_once("../clases/ClasesDocumentosExcel.php");
    session_start();    
    $idUser=$_SESSION['idUser'];
    $obVenta = new conexion($idUser);
    $obExcel=new TS5_Excel($db);
    $idDocumento=$obVenta->normalizar($_REQUEST["idDocumento"]);
    
    switch ($idDocumento){
        case 1: //Relacion de facturas en cobros prejuridicos
            $idCobro=$obVenta->normalizar($_REQUEST["idCobro"]);
            
            $obExcel->GenerarRelacionCobrosPrejuridicos($idCobro,"");
            
            break;
        
    }
}else{
    print("No se recibió parametro de documento");
}

?>