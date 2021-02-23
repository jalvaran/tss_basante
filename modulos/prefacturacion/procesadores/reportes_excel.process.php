<?php

@session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];

include_once("../clases/reportes_excel.class.php");

if( !empty($_REQUEST["Accion"]) ){
    $obCon = new TS_Excel($idUser);
    
    switch ($_REQUEST["Accion"]) {
        
        case 1: //genere reporte de entrega en excel
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $mipres_id=$obCon->normalizar($_REQUEST["mipres_id"]);
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $obCon->reporte_entrega_excel($datos_empresa,$mipres_id);
            print("OK;reporte generado");
            
        break; //fin caso 1
    
         
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>