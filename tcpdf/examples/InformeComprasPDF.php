<?php

include("../../modelo/php_conexion.php");

////////////////////////////////////////////
/////////////Obtengo el rango de fechas
////////////////////////////////////////////
$VerFacturas=0;
$obVenta = new conexion(1);
$fecha=date("Y-m-d");
$FechaIni = $obVenta->normalizar($_POST["TxtFechaIni"]);
$FechaFinal = $obVenta->normalizar($_POST["TxtFechaFinal"]);
$TipoReporte=$_POST["CmbTipoReporte"];

$Condicion=" relacioncompras rc INNER JOIN productosventa pv ON rc.ProductosVenta_idProductosVenta=pv.idProductosVenta WHERE ";
$Condicion2=" relacioncompras WHERE ";
if($TipoReporte=="Corte"){
    
    $CondicionFecha=" rc.Fecha <= '$FechaFinal' ";
    $CondicionFecha2=" Fecha <= '$FechaFinal' ";
    $Rango="Corte a $FechaFinal";
}else{
    
    $CondicionFecha=" rc.Fecha >= '$FechaIni' AND rc.Fecha <= '$FechaFinal' ";
    $CondicionFecha2=" Fecha >= '$FechaIni' AND Fecha <= '$FechaFinal' ";
    $Rango="De $FechaIni a $FechaFinal";
}

$Condicion=$Condicion.$CondicionFecha;
$Condicion2=$Condicion2.$CondicionFecha2;
$idFormatoCalidad=18;

$Documento="<strong>Informe De Compras $Rango</strong>";
require_once('Encabezado.php');

$nombre_file=$fecha."_Informe_Compras";
		   

///////////////////////////////////////////////////////
//////////////tabla con los datos de Compras clasificado por Departamentos//////////////////
////////////////////////////////////////////////////////


$tbl = <<<EOD


<span style="color:RED;font-family:'Bookman Old Style';font-size:12px;"><strong><em>Total de Compras Discriminadas por Departamento:
</em></strong></span><BR><BR>


<table border="1" cellspacing="2" align="center" >
  <tr> 
    <th><h3>Departamento</h3></th>
	<th><h3>Nombre</h3></th>
	<th><h3>Total Items</h3></th>
        <th><h3>SubTotal</h3></th>
	
  </tr >
  
</table>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

$sql="SELECT pv.Departamento as idDepartamento, SUM(rc.TotalAntesIVA) as Subtotal, SUM(rc.Cantidad) as Items"
        . "  FROM $Condicion GROUP BY pv.Departamento";

$Datos=$obVenta->Query($sql);

$Subtotal=0;

$TotalItems=0;
$flagQuery=0;   //para indicar si hay resultados
$i=0;

while($DatosCompras=$obVenta->FetchArray($Datos)){
        $flagQuery=1;	
        $SubtotalUser=number_format($DatosCompras["Subtotal"]);
        
        $Items=number_format($DatosCompras["Items"]);
        $DatosDepartamento=$obVenta->DevuelveValores("prod_departamentos", "idDepartamentos", $DatosCompras["idDepartamento"]);
        $NombreDep=$DatosDepartamento["Nombre"];

        $Subtotal=$Subtotal+$DatosCompras["Subtotal"];
        
        $TotalItems=$TotalItems+$DatosCompras["Items"];
        $idDepartamentos=$DatosCompras["idDepartamento"];


        $tbl = <<<EOD

<table border="1" cellpadding="2"  align="center">
 <tr>
  <td>$idDepartamentos</td>
  <td>$NombreDep</td>
  <td>$Items</td>
  <td>$SubtotalUser</td>
  
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

}
	
if($flagQuery==1){
$TotalItems=number_format($TotalItems);
$Subtotal=number_format($Subtotal);

$tbl = <<<EOD

<table border="1" cellspacing="2" align="center">
 <tr>
  <td align="RIGHT"><h3>SUMATORIA</h3></td>
  <td><h3>NA</h3></td>
  <td><h3>$TotalItems</h3></td>
  <td><h3>$Subtotal</h3></td>
  
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	
        }

///////////////////////////////////////////////////////
//////////////tabla con los datos de Compras por proveedor//////////////////
////////////////////////////////////////////////////////


$tbl = <<<EOD
<br><br>

<span style="color:RED;font-family:'Bookman Old Style';font-size:12px;"><strong><em>Total de Compras Discriminadas por Proveedor:
</em></strong></span><BR><BR>


<table border="1" cellspacing="2" align="center" >
  <tr> 
    <th><h3>NIT</h3></th>
    <th><h3>Nombre</h3></th>
    <th><h3>Total Items</h3></th>
    <th><h3>SubTotal</h3></th>
	
  </tr >
  
</table>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

$sql="SELECT idProveedor, SUM(TotalAntesIVA) as Subtotal, SUM(Cantidad) as Items"
        . "  FROM $Condicion2 GROUP BY idProveedor";

$Datos=$obVenta->Query($sql);

$Subtotal=0;

$TotalItems=0;
$flagQuery=0;   //para indicar si hay resultados
$i=0;

while($DatosCompras=$obVenta->FetchArray($Datos)){
        $flagQuery=1;	
        $SubtotalUser=number_format($DatosCompras["Subtotal"]);
        
        $Items=number_format($DatosCompras["Items"]);
        $DatosProveedor=$obVenta->DevuelveValores("proveedores", "idProveedores", $DatosCompras["idProveedor"]);
        $NIT=$DatosProveedor["Num_Identificacion"];
        $Nombre=$DatosProveedor["RazonSocial"];
        $Subtotal=$Subtotal+$DatosCompras["Subtotal"];
        
        $TotalItems=$TotalItems+$DatosCompras["Items"];
        


        $tbl = <<<EOD

<table border="1" cellpadding="2"  align="center">
 <tr>
  <td>$NIT</td>
  <td>$Nombre</td>
  <td>$Items</td>
  <td>$SubtotalUser</td>
  
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

}
	
if($flagQuery==1){
$TotalItems=number_format($TotalItems);
$Subtotal=number_format($Subtotal);

$tbl = <<<EOD

<table border="1" cellspacing="2" align="center">
 <tr>
  <td align="RIGHT"><h3>SUMATORIA</h3></td>
  <td><h3>NA</h3></td>
  <td><h3>$TotalItems</h3></td>
  <td><h3>$Subtotal</h3></td>
  
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	
        }

///////////////////////////////////////////////////////
//////////////tabla con los datos de Compras discriminadas por usuario//////////////////
////////////////////////////////////////////////////////


$tbl = <<<EOD
<br><br>

<span style="color:RED;font-family:'Bookman Old Style';font-size:12px;"><strong><em>Total de Compras Discriminadas por Usuario:
</em></strong></span><BR><BR>


<table border="1" cellspacing="2" align="center" >
  <tr> 
    <th><h3>Documento</h3></th>
    <th><h3>Nombre</h3></th>
    <th><h3>Total Items</h3></th>
    <th><h3>SubTotal</h3></th>
	
  </tr >
  
</table>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

$sql="SELECT idUsuario, SUM(TotalAntesIVA) as Subtotal, SUM(Cantidad) as Items"
        . "  FROM $Condicion2 GROUP BY idUsuario";

$Datos=$obVenta->Query($sql);

$Subtotal=0;

$TotalItems=0;
$flagQuery=0;   //para indicar si hay resultados
$i=0;

while($DatosCompras=$obVenta->FetchArray($Datos)){
        $flagQuery=1;	
        $SubtotalUser=number_format($DatosCompras["Subtotal"]);
        
        $Items=number_format($DatosCompras["Items"]);
        $DatosUsuario=$obVenta->DevuelveValores("usuarios", "idUsuarios", $DatosCompras["idUsuario"]);
        $NIT=$DatosUsuario["Identificacion"];
        $Nombre=$DatosUsuario["Nombre"]." ".$DatosUsuario["Apellido"];
        $Subtotal=$Subtotal+$DatosCompras["Subtotal"];
        
        $TotalItems=$TotalItems+$DatosCompras["Items"];
        


        $tbl = <<<EOD

<table border="1" cellpadding="2"  align="center">
 <tr>
  <td>$NIT</td>
  <td>$Nombre</td>
  <td>$Items</td>
  <td>$SubtotalUser</td>
  
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

}
	
if($flagQuery==1){
$TotalItems=number_format($TotalItems);
$Subtotal=number_format($Subtotal);

$tbl = <<<EOD

<table border="1" cellspacing="2" align="center">
 <tr>
  <td align="RIGHT"><h3>SUMATORIA</h3></td>
  <td><h3>NA</h3></td>
  <td><h3>$TotalItems</h3></td>
  <td><h3>$Subtotal</h3></td>
  
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