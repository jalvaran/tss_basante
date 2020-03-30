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
$TipoReporte=$_POST["CmbTipoReporte"];
$TipoFiltro=$_POST["CmbFiltro"];
$CuentaPUC=$_POST["TxtCuentaPUC"];
$Tercero=$_POST["TxtTercero"];
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
	
if($TipoFiltro=="Igual"){
	$Condicion.="  AND CuentaPUC='$CuentaPUC' ";
        $Condicion2.="  AND CuentaPUC='$CuentaPUC' AND ";
}

if($TipoFiltro=="Inicia"){
	$Condicion.="  AND CuentaPUC LIKE '$CuentaPUC%' ";
        $Condicion2.="  AND CuentaPUC LIKE '$CuentaPUC%' AND ";
}

if($Tercero<>"All"){
	$Condicion.="  AND Tercero_Identificacion = '$Tercero' ";
        $Condicion2.="  AND Tercero_Identificacion = '$Tercero' AND ";
}
$idFormatoCalidad=19;

$Documento="<strong>AUXILIAR DETALLADO $Rango</strong>";
require_once('Encabezado.php');
		 
$nombre_file=$fecha."_AuxiliarDetallado";
		   
///////////////////////////////////////////////////////
//////////////tabla con los datos//////////////////
////////////////////////////////////////////////////////
//print("$Condicion");
$pdf->SetFont('helvetica', '', 6);
$Titulo="";
$tbl=$obTabla->ArmeTablaAuxiliarDetallado($Titulo,$Condicion,$TipoReporte,$FechaIni,"");
$pdf->writeHTML($tbl, false, false, false, false, '');

//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');


//============================================================+
// END OF FILE
//============================================================+
?>