<?php

include("../../modelo/php_conexion.php");

////////////////////////////////////////////
/////////////Obtengo el rango de fechas
////////////////////////////////////////////
$VerFacturas=0;
$obVenta = new conexion(1);
$fecha=date("Y-m-d");
$FechaIni = $_POST["TxtFechaIni"];
$FechaFinal = $_POST["TxtFechaFinal"];
$CentroCostos=$_POST["CmbCentroCostos"];
$EmpresaPro=$_POST["CmbEmpresaPro"];
$TipoReporte=$_POST["CmbTipoReporte"];

$Condicion=" titulos_ventas WHERE ";
//$Condicion2="ori_facturas WHERE ";
if($TipoReporte=="Corte"){
    $CondicionFecha1=" Fecha <= '$FechaFinal' ";
    //$CondicionFecha2=" Fecha <= '$FechaFinal' ";
    $Rango="Corte a $FechaFinal";
}else{
    $CondicionFecha1=" Fecha >= '$FechaIni' AND Fecha <= '$FechaFinal' ";
    //$CondicionFecha2=" Fecha >= '$FechaIni' AND Fecha <= '$FechaFinal' ";
    $Rango="De $FechaIni a $FechaFinal";
}

$CondicionItems=$Condicion.$CondicionFecha1;
//$CondicionFacturas=$Condicion2.$CondicionFecha2;
/*
if($CentroCostos<>"ALL"){
	$Condicion.="  AND idCentroCosto='$CentroCostos' ";
}
	
if($EmpresaPro<>"ALL"){
	$Condicion.="  AND idEmpresa='$EmpresaPro' ";
}
*/

$idFormatoCalidad=16;

$Documento="<strong>Informe De Ventas $Rango</strong>";
require_once('Encabezado.php');

$nombre_file=$fecha."_Reporte_Administracion";
		   

///////////////////////////////////////////////////////
//////////////tabla con los datos de ventas//////////////////
////////////////////////////////////////////////////////


$tbl = <<<EOD


<span style="color:RED;font-family:'Bookman Old Style';font-size:12px;"><strong><em>Total de Ventas Discriminadas por Promocion:
</em></strong></span><BR><BR>


<table border="1" cellspacing="2" align="center" >
  <tr> 
    <th><h3>Promocion</h3></th>
    <th><h3>Nombre</h3></th>
    <th><h3>Total Titulos</h3></th>
    
    <th><h3>Total Saldos</h3></th>
    <th><h3>Total Abonos</h3></th>
    <th><h3>Valor Total</h3></th>
  </tr >
  
</table>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

$sql="SELECT Promocion as Promocion, COUNT(Mayor1) as TotalItems, SUM(Valor) as ValorTotal, "
        . "SUM(TotalAbonos) as TotalAbonos, SUM(Saldo) as TotalSaldo"
        . " "
        . "  FROM $CondicionItems AND Estado='' GROUP BY Promocion";

$Datos=$obVenta->Query($sql);

$TotalVentas=0;
$TotalAbonos=0;
$TotalSaldo=0;
$TotalItems=0;
$TotalTitulosPagos=0;
$flagQuery=0;   //para indicar si hay resultados
$i=0;

while($DatosVentas=$obVenta->FetchArray($Datos)){
        $flagQuery=1;	
        
        $Abonos=number_format($DatosVentas["TotalAbonos"]);
        $Total=number_format($DatosVentas["ValorTotal"]);
        $Items=number_format($DatosVentas["TotalItems"]);
        $Saldo=number_format($DatosVentas["TotalSaldo"]);
        //$TitulosPagos=number_format($DatosVentas["TotalTitulosPagos"]);
        $DatosPromocion=$obVenta->DevuelveValores("titulos_promociones", "ID", $DatosVentas["Promocion"]);
        $NombrePromocion=$DatosPromocion["Nombre"];

        $TotalVentas=$TotalVentas+$DatosVentas["ValorTotal"];
        $TotalAbonos=$TotalAbonos+$DatosVentas["TotalAbonos"];
        $TotalSaldo=$TotalSaldo+$DatosVentas["TotalSaldo"];
        $TotalItems=$TotalItems+$DatosVentas["TotalItems"];
        //$TotalTitulosPagos=$TotalTitulosPagos+$DatosVentas["TotalTitulosPagos"];
        $idPromocion=$DatosVentas["Promocion"];


        $tbl = <<<EOD

<table border="1" cellpadding="2"  align="center">
 <tr>
  <td>$idPromocion</td>
  <td>$NombrePromocion</td>
  <td>$Items</td>
  
  <td>$Saldo</td>
  <td>$Abonos</td>
  <td>$Total</td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

}
	
if($flagQuery==1){
$TotalItems=number_format($TotalItems);
$TotalVentas=number_format($TotalVentas);
$TotalSaldo=number_format($TotalSaldo);
$TotalAbonos=number_format($TotalAbonos);
$TotalTitulosPagos=number_format($TotalTitulosPagos);
$tbl = <<<EOD

<table border="1" cellspacing="2" align="center">
 <tr>
  <td align="RIGHT"><h3>SUMATORIA</h3></td>
  <td><h3>NA</h3></td>
  <td><h3>$TotalItems</h3></td>
  
  <td><h3>$TotalSaldo</h3></td>
  <td><h3>$TotalAbonos</h3></td>
  <td><h3>$Total</h3></td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	
}

$sql="SELECT Promocion,COUNT(Mayor1) as TotalTitulosPagos,SUM(Valor) as TotalValor FROM $CondicionItems AND Saldo=0 AND Estado='' GROUP BY Promocion";
$Datos=$obVenta->Query($sql);
while($DatosVentas=$obVenta->FetchArray($Datos)){
 $idPromocion=   $DatosVentas["Promocion"];
 $ItemsPagos= number_format($DatosVentas["TotalTitulosPagos"]);
 $Valor= number_format($DatosVentas["TotalValor"]);
$tbl = <<<EOD
<BR><BR><h3>RESUMEN TITULOS PAGADOS:</h3><BR><BR>
<table border="1" cellpadding="2"  align="center">
 <tr>
  <td><h3>Promocion:</h3></td>      
  <td>$idPromocion</td>
  <td><h3>Total:</h3></td>
  <td>$ItemsPagos</td>
  <td><h3>Valor:</h3></td>
  
  <td>$Valor</td>
  
 </tr>
 </table>
        
        <BR><BR><h3>RESUMEN CARTERA:</h3><BR><BR>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
}


$sql="SELECT Promocion,COUNT(Mayor1) as TotalTitulosPagos,SUM(Saldo) as TotalValor FROM $CondicionItems AND  Estado='' AND Saldo>0 AND Saldo<130000 GROUP BY Promocion";
$Datos=$obVenta->Query($sql);
while($DatosVentas=$obVenta->FetchArray($Datos)){
 $idPromocion=   $DatosVentas["Promocion"];
 $ItemsPagos= number_format($DatosVentas["TotalTitulosPagos"]);
 $Valor= number_format($DatosVentas["TotalValor"]);
$tbl = <<<EOD

<table border="1" cellpadding="2"  align="center">
 <tr>
  <td><h3>CARTERA CON CON SALDO INFERIOR A $130.000:</h3></td>      
  <td>$idPromocion</td>
  <td><h3>Total:</h3></td>
  <td>$ItemsPagos</td>
  <td><h3>Valor:</h3></td>
  
  <td>$Valor</td>
  
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
}

$sql="SELECT Promocion,COUNT(Mayor1) as TotalTitulosPagos,SUM(Saldo) as TotalValor FROM $CondicionItems AND Estado='' AND Saldo>=130000 GROUP BY Promocion";
$Datos=$obVenta->Query($sql);
while($DatosVentas=$obVenta->FetchArray($Datos)){
 $idPromocion=   $DatosVentas["Promocion"];
 $ItemsPagos= number_format($DatosVentas["TotalTitulosPagos"]);
 $Valor= number_format($DatosVentas["TotalValor"]);
$tbl = <<<EOD

<table border="1" cellpadding="2"  align="center">
 <tr>
  <td><h3>CARTERA CON CON SALDO SUPERIOR A $130.000:</h3></td>      
  <td>$idPromocion</td>
  <td><h3>Total:</h3></td>
  <td>$ItemsPagos</td>
  <td><h3>Valor:</h3></td>
  
  <td>$Valor</td>
  
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
}
//$pdf->writeHTML($tab, false, false, false, false, '');
//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>