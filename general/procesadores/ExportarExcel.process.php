<?php

@session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];

include_once("../clases/ClasesDocumentosExcel.class.php");

if( !empty($_REQUEST["Accion"]) ){
    $obCon = new TS_Excel($idUser);
    
    switch ($_REQUEST["Accion"]) {
        
        case 1: //exportar una tabla a excel
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $Tabla=$obCon->normalizar($_REQUEST["tb"]);
            $sql= urldecode(base64_decode($_REQUEST["st"]));
            $columnas= urldecode(base64_decode($_REQUEST["cols"]));
            $array_cols= explode(",", $columnas);
            $obCon->exportar_tabla($Tabla, $sql, $array_cols);
            print("OK;tabla $Tabla exportada");
            
        break; //fin caso 1
        
                
        
            
        
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>