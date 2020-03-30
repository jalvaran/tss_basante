<?php
include_once("../../modelo/php_conexion.php");
include_once("../css_construct.php");
$css =  new CssIni("");
$obVenta = new conexion(1);
$tbl=$obVenta->normalizar($_REQUEST["Tbl"]);
$Campo=$obVenta->normalizar($_REQUEST["Campo"]);
$idElement=$obVenta->normalizar($_REQUEST["idElement"]);
$consulta=$obVenta->ConsultarTabla("tablas_campos_control", "WHERE NombreTabla='$tbl' AND Campo='$Campo' AND Habilitado=1");
$DatosCampo=$obVenta->FetchArray($consulta);
if($DatosCampo["Visible"]<>""){
    if($DatosCampo["Visible"]==1){
        $obVenta->ActualizaRegistro("tablas_campos_control", "Visible", 0, "ID", $DatosCampo["ID"]);
        print("$Campo de $tbl a 0 ");
        //print("<script>CambiarImagenOnOff($idElement);</script>");
    }
    if($DatosCampo["Visible"]==0){
        $obVenta->ActualizaRegistro("tablas_campos_control", "Visible", 1, "ID", $DatosCampo["ID"]);
        //print("<script>CambiarImagenOnOff($idElement);</script>");
        print("$Campo de $tbl a 1");
    }
}else{
    $sql="INSERT INTO `tablas_campos_control` (`ID`, `NombreTabla`, `Campo`, `Visible`, `Editable`, `Habilitado`, `TipoUser`, `idUser`) 
        VALUES ('', '$tbl', '$Campo', 0, 1, 1, 'administrador', 3);";
    $obVenta->Query($sql);
    
}


?>