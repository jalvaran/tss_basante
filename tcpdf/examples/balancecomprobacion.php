<?php

include("../../modelo/php_conexion.php");
include("../../modelo/php_tablas.php");

////////////////////////////////////////////
/////////////Obtengo el rango de fechas
////////////////////////////////////////////
$obVenta = new conexion(1);
$obTabla = new Tabla($db);
$sql="UPDATE `subcuentas` SET `Nombre`=replace(`Nombre`,'á','a'),"
        . "`Nombre`=replace(`Nombre`,'é','e'),"
        . "`Nombre`=replace(`Nombre`,'í','i'),"
        . "`Nombre`=replace(`Nombre`,'ó','o'),"
        . "`Nombre`=replace(`Nombre`,'ú','u') ";
$obVenta->Query($sql);//Eliminamos tildes
$fecha=date("Y-m-d");
$FechaIni = $_POST["TxtFechaIni"];
$FechaFinal = $_POST["TxtFechaFinal"];
$FechaCorte = $_POST["TxtFechaCorte"];
$CentroCostos=$_POST["CmbCentroCostos"];
$EmpresaPro=$_POST["CmbEmpresaPro"];
$TipoReporte=$_POST["CmbTipoReporte"];


$Condicion=" WHERE ";
$Condicion2=" WHERE ";
if($TipoReporte=="Corte"){
    
    $Condicion.=" Fecha <= '$FechaCorte' ";
    $Condicion2.=" Fecha > '5000-01-01' AND  ";
    $Rango="Corte a $FechaCorte";
}else{
    $Condicion.=" Fecha >= '$FechaIni' AND Fecha <= '$FechaFinal' "; 
    $Condicion2.= " Fecha < '$FechaIni' AND ";
    $Rango="De $FechaIni a $FechaFinal";
}
if($CentroCostos<>"ALL"){
	$Condicion.="  AND idCentroCosto='$CentroCostos' ";
        $Condicion2.="  AND idCentroCosto='$CentroCostos' AND ";
}
	
if($EmpresaPro<>"ALL"){
	$Condicion.="  AND idEmpresa='$EmpresaPro' ";
        $Condicion2.="  AND idEmpresa='$EmpresaPro' AND ";
}

$idFormatoCalidad=20;

$Documento="<strong>BALANCE DE COMPROBACION $Rango</strong>";
require_once('Encabezado.php');
		 
$nombre_file=$fecha."_BalanceComprobacion";
		   
///////////////////////////////////////////////////////
//////////////tabla con los datos//////////////////
////////////////////////////////////////////////////////

$Titulo="";

$tbl=$obTabla->ArmeTablaBalanceComprobacion($Titulo,$Condicion,$Condicion2,"");
//print($tbl);
$pdf->writeHTML($tbl, false, false, false, false, '');

//$pdf->writeHTML($tab, false, false, false, false, '');
//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');


//============================================================+
// END OF FILE
//============================================================+
?>