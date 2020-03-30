<?php

include("../../modelo/php_conexion.php");
include("../../modelo/PrintPos.php");
////////////////////////////////////////////
/////////////Obtengo el rango de fechas
////////////////////////////////////////////
$VerFacturas=0;
$obVenta = new conexion(1);
$obPrintPos = new PrintPos(1);
$fecha=date("Y-m-d");
$FechaIni = $obVenta->normalizar($_POST["TxtFechaIni"]);
$FechaFinal =$obVenta->normalizar($_POST["TxtFechaFinal"]);
$CentroCostos=$obVenta->normalizar($_POST["CmbCentroCostos"]);
$EmpresaPro=$obVenta->normalizar($_POST["CmbEmpresaPro"]);
$TipoReporte=$obVenta->normalizar($_POST["CmbTipoReporte"]);

$Condicion=" ori_facturas_items WHERE ";
$Condicion2="ori_facturas WHERE ";
if($TipoReporte=="Corte"){
    $CondicionFecha1=" FechaFactura <= '$FechaFinal' ";
    $CondicionFecha2=" Fecha <= '$FechaFinal' ";
    $Rango="Corte a $FechaFinal";
}else{
    $CondicionFecha1=" FechaFactura >= '$FechaIni' AND FechaFactura <= '$FechaFinal' ";
    $CondicionFecha2=" Fecha >= '$FechaIni' AND Fecha <= '$FechaFinal' ";
    $Rango="De $FechaIni a $FechaFinal";
}

$CondicionItems=$Condicion.$CondicionFecha1;
$CondicionFacturas=$Condicion2.$CondicionFecha2;
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


<span style="color:RED;font-family:'Bookman Old Style';font-size:12px;"><strong><em>Total de Ventas Discriminadas por Departamento:
</em></strong></span><BR><BR>


<table border="1" cellspacing="2" align="center" >
  <tr> 
    <th><h3>Departamento</h3></th>
	<th><h3>Nombre</h3></th>
	<th><h3>Total Items</h3></th>
    <th><h3>SubTotal</h3></th>
	<th><h3>IVA</h3></th>
	<th><h3>Total</h3></th>
  </tr >
  
</table>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

$sql="SELECT Departamento as idDepartamento, SUM(SubtotalItem) as Subtotal, SUM(IVAItem) as IVA, SUM(TotalItem) as Total, SUM(Cantidad) as Items"
        . "  FROM $CondicionItems GROUP BY Departamento";

$Datos=$obVenta->Query($sql);

$Subtotal=0;
$TotalIVA=0;
$TotalVentas=0;
$TotalItems=0;
$flagQuery=0;   //para indicar si hay resultados
$i=0;

while($DatosVentas=$obVenta->FetchArray($Datos)){
        $flagQuery=1;	
        $SubtotalUser=number_format($DatosVentas["Subtotal"]);
        $IVA=number_format($DatosVentas["IVA"]);
        $Total=number_format($DatosVentas["Total"]);
        $Items=number_format($DatosVentas["Items"]);
        $DatosDepartamento=$obVenta->DevuelveValores("prod_departamentos", "idDepartamentos", $DatosVentas["idDepartamento"]);
        $NombreDep=$DatosDepartamento["Nombre"];

        $Subtotal=$Subtotal+$DatosVentas["Subtotal"];
        $TotalIVA=$TotalIVA+$DatosVentas["IVA"];
        $TotalVentas=$TotalVentas+$DatosVentas["Total"];
        $TotalItems=$TotalItems+$DatosVentas["Items"];
        $idDepartamentos=$DatosVentas["idDepartamento"];


        $tbl = <<<EOD

<table border="1" cellpadding="2"  align="center">
 <tr>
  <td>$idDepartamentos</td>
  <td>$NombreDep</td>
  <td>$Items</td>
  <td>$SubtotalUser</td>
  <td>$IVA</td>
  <td>$Total</td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

}
	
if($flagQuery==1){
$TotalItems=number_format($TotalItems);
$Subtotal=number_format($Subtotal);
$TotalIVA=number_format($TotalIVA);
$TotalVentas=number_format($TotalVentas);
$tbl = <<<EOD

<table border="1" cellspacing="2" align="center">
 <tr>
  <td align="RIGHT"><h3>SUMATORIA</h3></td>
  <td><h3>NA</h3></td>
  <td><h3>$TotalItems</h3></td>
  <td><h3>$Subtotal</h3></td>
  <td><h3>$TotalIVA</h3></td>
  <td><h3>$TotalVentas</h3></td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	
        }

/*
///////////////////////////////////////////////////////
//////////////tabla con los datos de ventas//////////////////
////////////////////////////////////////////////////////


$tbl = <<<EOD


<br><br><span style="color:RED;font-family:'Bookman Old Style';font-size:12px;"><strong><em>Total de Ventas Discriminadas por Usuarios y Tipo de Venta:
</em></strong></span><BR><BR>


<table border="1" cellspacing="2" align="center" >
  <tr> 
    <th><h3>Usuario</h3></th>
	<th><h3>TipoVenta</h3></th>
	<th><h3>Total Costos</h3></th>
    <th><h3>SubTotal</h3></th>
	<th><h3>IVA</h3></th>
	<th><h3>Total</h3></th>
  </tr >
  
</table>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');


$sql="SELECT Usuarios_idUsuarios as IdUsuarios, FormaPago as  TipoVenta, SUM(Subtotal) as Subtotal, SUM(IVA) as IVA, 
SUM(Total) as Total, SUM(TotalCostos) as TotalCostos"
        . "  FROM $CondicionFacturas GROUP BY Usuarios_idUsuarios, FormaPago";

$Datos=$obVenta->Query($sql);




$Subtotal=0;
$TotalIVA=0;
$TotalVentas=0;
$TotalCostos=0;
$flagQuery=0;
$i=0;
while($DatosVentas=$obVenta->FetchArray($Datos)){
        $flagQuery=1;
        $SubtotalUser=number_format($DatosVentas["Subtotal"]);
        $IVA=number_format($DatosVentas["IVA"]);
        $Total=number_format($DatosVentas["Total"]);
        $Costos=number_format($DatosVentas["TotalCostos"]);
        $TipoVenta=$DatosVentas["TipoVenta"];

        $Subtotal=$Subtotal+$DatosVentas["Subtotal"];
        $TotalIVA=$TotalIVA+$DatosVentas["IVA"];
        $TotalVentas=$TotalVentas+$DatosVentas["Total"];
        $TotalCostos=$TotalCostos+$DatosVentas["TotalCostos"];
        $idUser=$DatosVentas["IdUsuarios"];


        $tbl = <<<EOD

<table border="1" cellpadding="2"  align="center">
<tr>
<td>$idUser</td>
<td>$TipoVenta</td>
<td>$Costos</td>
<td>$SubtotalUser</td>
<td>$IVA</td>
<td>$Total</td>
</tr>
</table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
    }

if($flagQuery==1){
    $TotalCostos=number_format($TotalCostos);
    $Subtotal=number_format($Subtotal);
    $TotalIVA=number_format($TotalIVA);
    $TotalVentas=number_format($TotalVentas);
    $tbl = <<<EOD

<table border="1" cellspacing="2" align="center">
<tr>
<td align="RIGHT"><h3>SUMATORIA</h3></td>
<td><h3>NA</h3></td>
<td><h3>$TotalCostos</h3></td>
<td><h3>$Subtotal</h3></td>
<td><h3>$TotalIVA</h3></td>
<td><h3>$TotalVentas</h3></td>
</tr>
</table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');


}

*/
////////////////////////////////////////////////////////////////////////////////
/////////////////////////FACTURAS NUMERACION////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$tbl = <<<EOD


<BR><BR><span style="color:RED;font-family:'Bookman Old Style';font-size:12px;"><strong><em>Informe de Numeracion Facturas:
</em></strong></span><BR><BR>


<table border="1" cellspacing="2" align="center" >
  <tr> 
    <th><h3>Resolucion</h3></th>
    <th><h3>Factura Inicial</h3></th>
    <th><h3>Factura Final</h3></th>
    <th><h3>Total Clientes</h3></th>
	
  </tr >
  
</table>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

$sql="SELECT idResolucion,MAX(NumeroFactura) as MaxFact, MIN(NumeroFactura) as MinFact FROM facturas
	WHERE $CondicionFecha2 GROUP BY idResolucion";
$sel1=$obVenta->Query($sql);


while($DatosNumFact=$obVenta->FetchArray($sel1)){
	$MinFact=$DatosNumFact["MinFact"];
	$MaxFact=$DatosNumFact["MaxFact"];
        $idResolucion=$DatosNumFact["idResolucion"];
	$TotalFacts=$MaxFact-$MinFact+1;
	
	
	
$tbl = <<<EOD

<table border="1"  cellpadding="2" align="center">
 <tr>
  <td>$idResolucion</td>
  <td>$MinFact</td>
  <td>$MaxFact</td>
  <td>$TotalFacts</td>
 
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	
}


////////////////////////////////////////////////////////////////////////////////
/////////////////////////TIPO VENTAS////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$tbl = <<<EOD


<BR><BR><span style="color:RED;font-family:'Bookman Old Style';font-size:12px;"><strong><em>Tipo de Ventas:
</em></strong></span><BR><BR>


<table border="1" cellspacing="2" align="center" >
  <tr> 
    <th><h3>Efectivo</h3></th>
    <th><h3>Ventas al Detal</h3></th>
    <th><h3>Ventas al Por mayor</h3></th>
    
	
  </tr >
  <tr> 
    <th><h3>$TotalVentas</h3></th>
    <th><h3>$TotalVentas</h3></th>
    <th><h3>0</h3></th>
    
	
  </tr >
</table>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

//$pdf->writeHTML($tab, false, false, false, false, '');
//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');
$DatosImpresora=$obVenta->DevuelveValores("config_puertos", "ID", 1);
    if($DatosImpresora["Habilitado"]=="SI"){
        $obPrintPos->ImprimeComprobanteInformeDiario($DatosImpresora["Puerto"], $FechaIni, $FechaFinal);
        
    }
    
//============================================================+
// END OF FILE
//============================================================+
?>