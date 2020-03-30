<?php

include("../../modelo/php_conexion.php");
include("../../modelo/php_tablas.php");

////////////////////////////////////////////
/////////////Obtengo el rango de fechas
////////////////////////////////////////////
$obVenta = new conexion(1);
$obTabla = new Tabla($db);

$fecha=date("Y-m-d");

$FechaCorte = $_POST["TxtFechaCorteBalance"];
$CentroCostos=$_POST["CmbCentroCostos"];
$EmpresaPro=$_POST["CmbEmpresaPro"];



$Condicion=" WHERE ";

$Condicion.=" Fecha <= '$FechaCorte' ";
    
if($CentroCostos<>"ALL"){
	$Condicion.="  AND idCentroCosto='$CentroCostos' ";
        
}
	
if($EmpresaPro<>"ALL"){
	$Condicion.="  AND idEmpresa='$EmpresaPro' ";
        
}

$idFormatoCalidad=15;

$Documento="<strong>BALANCE GENERAL FECHA DE CORTE $FechaCorte</strong>";
require_once('Encabezado.php');
		 
$nombre_file=$FechaCorte."_BalanceGeneral";
		   
///////////////////////////////////////////////////////
//////////////tabla con los datos//////////////////
////////////////////////////////////////////////////////

$Titulo="";

$tbl=$obTabla->ArmeTablaBalanceGeneral($Titulo,$Condicion,"");
//print($tbl);
$pdf->writeHTML($tbl, false, false, false, false, '');

//$pdf->writeHTML($tab, false, false, false, false, '');
//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');


//============================================================+
// END OF FILE
//============================================================+
?>