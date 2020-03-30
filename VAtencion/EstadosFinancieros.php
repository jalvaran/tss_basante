<?php 
$myPage="EstadosFinancieros.php";
include_once("../sesiones/php_control.php");
include_once("clases/ClasesInformesFinancieros.php");
$obVenta = new conexion($idUser);
$obTabla = new Tabla($db);
$obInformes = new InformesFinancieros($db);
//Si se recibe Balance General
if(isset($_REQUEST["BtnVerInforme"])){
    
    $FechaCorte = $obVenta->normalizar($_POST["TxtFechaCorteBalance"]);
    $CentroCostos=$obVenta->normalizar($_POST["CmbCentroCostos"]);
    $EmpresaPro=$obVenta->normalizar($_POST["CmbEmpresaPro"]);

    $obTabla->GenereEstadosFinancierosPDF($FechaCorte,$CentroCostos,$EmpresaPro,"");
}
//Si se recibe estado de resultados
if(isset($_REQUEST["BtnVerEstadoResultados"])){
    
    $FechaCorte = $obVenta->normalizar($_POST["TxtFechaCorteBalance"]);
    $FechaInicial = $obVenta->normalizar($_POST["TxtFechaIniER"]);
    $FechaFinal = $obVenta->normalizar($_POST["TxtFechaFinER"]);
    $CentroCosto=$obVenta->normalizar($_POST["CmbCentroCostos"]);
    $idEmpresa=$obVenta->normalizar($_POST["CmbEmpresaPro"]);
    $TipoReporte=$obVenta->normalizar($_POST["CmbTipoReporteER"]);

    $obInformes->EstadosResultados_PDF($TipoReporte, $FechaInicial, $FechaFinal, $FechaCorte, $idEmpresa, $CentroCosto, "");
}

?>