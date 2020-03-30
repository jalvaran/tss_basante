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

$Condicion=" facturas_items WHERE ";
$Condicion2=" facturas WHERE ";
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


///////////////////////////////////////////////////////
//////////////tabla con los datos DE LAS DEVOLUCIONES//////////////////
////////////////////////////////////////////////////////


$tbl = <<<EOD


<BR><BR><span style="color:RED;font-family:'Bookman Old Style';font-size:12px;"><strong><em>Total devoluciones:
</em></strong></span><BR><BR>


<table border="1" cellspacing="2" align="center" >
  <tr> 
    <th><h3>Usuario</h3></th>
	<th><h3>Total Items</h3></th>
    
	<th><h3>Total</h3></th>
  </tr >
  
</table>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');



	$sql="SELECT idUsuarios as IdUsuarios, Sum(Cantidad) as Items, 
SUM(TotalItem) as Total "
        . "  FROM facturas_items WHERE Cantidad < 0 AND $CondicionFecha1 GROUP BY idUsuarios";

        $sel1=$obVenta->Query($sql);

if($obVenta->NumRows($sel1)){
	
	$TotalVentas=0;
	$TotalItems=0;
	
	$i=0;
	while($DatosVentas=$obVenta->FetchArray($sel1)){
			
		
		$Total=number_format($DatosVentas["Total"]);
		$Items=number_format($DatosVentas["Items"]);
	
		$TotalVentas=$TotalVentas+$DatosVentas["Total"];
		$TotalItems=$TotalItems+$DatosVentas["Items"];
		$idUser=$DatosVentas["IdUsuarios"];
		
		
		$tbl = <<<EOD

<table border="1"  cellpadding="2" align="center">
 <tr>
  <td>$idUser</td>
  <td>$Items</td>
  
  <td>$Total</td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	}
	
	$TotalItems=number_format($TotalItems);
	
	$TotalVentas=number_format($TotalVentas);
	$tbl = <<<EOD

<table border="1" cellspacing="2" align="center">
 <tr>
  <td align="RIGHT"><h3>SUMATORIA</h3></td>
  <td><h3>$TotalItems</h3></td>
  
  <td><h3>$TotalVentas</h3></td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	
}


///////////////////////////////////////////////////////
//////////////tabla con los datos DE Los egresos//////////////////
////////////////////////////////////////////////////////


$tbl = <<<EOD


<BR><BR><span style="color:RED;font-family:'Bookman Old Style';font-size:12px;"><strong><em>Total Egresos:
</em></strong></span><BR><BR>


<table border="1" cellspacing="2" align="center" >
  <tr> 
    <th><h3>Usuario</h3></th>
    <th><h3>SubTotal</h3></th>
	<th><h3>IVA</h3></th>
	<th><h3>Total</h3></th>
  </tr >
  
</table>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

        $sql="SELECT Usuario_idUsuario as IdUsuarios, SUM(Subtotal) as Subtotal, SUM(IVA) as IVA, SUM(Valor) as Total FROM egresos
	WHERE $CondicionFecha2 AND Cuenta LIKE '110510%'
	GROUP BY Usuario_idUsuario";
        $sel1=$obVenta->Query($sql);
	
if($obVenta->NumRows($sel1)){
	$Subtotal=0;
	$TotalIVA=0;
	$TotalVentas=0;
	$TotalItems=0;
	
	$i=0;
	while($DatosVentas=$obVenta->FetchArray($sel1)){
			
		$SubtotalUser=number_format($DatosVentas["Subtotal"]);
		$IVA=number_format($DatosVentas["IVA"]);
		$Total=number_format($DatosVentas["Total"]);
		
		
		$Subtotal=$Subtotal+$DatosVentas["Subtotal"];
		$TotalIVA=$TotalIVA+$DatosVentas["IVA"];
		$TotalVentas=$TotalVentas+$DatosVentas["Total"];
		
		$idUser=$DatosVentas["IdUsuarios"];
		
		
		$tbl = <<<EOD

<table border="1"  cellpadding="2" align="center">
 <tr>
  <td>$idUser</td>
  
  <td>$SubtotalUser</td>
  <td>$IVA</td>
  <td>$Total</td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	}
	
	$TotalItems=number_format($TotalItems);
	$Subtotal=number_format($Subtotal);
	$TotalIVA=number_format($TotalIVA);
	$TotalVentas=number_format($TotalVentas);
	$tbl = <<<EOD

<table border="1" cellspacing="2" align="center">
 <tr>
  <td align="RIGHT"><h3>SUMATORIA</h3></td>
  
  <td><h3>$Subtotal</h3></td>
  <td><h3>$TotalIVA</h3></td>
  <td><h3>$TotalVentas</h3></td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	
}


///////////////////////////////////////////////////////
//////////////tabla con los datos DE Los Abonos//////////////////
////////////////////////////////////////////////////////


$tbl = <<<EOD


<BR><BR><span style="color:RED;font-family:'Bookman Old Style';font-size:12px;"><strong><em>Total Abonos:
</em></strong></span><BR><BR>


<table border="1" cellspacing="2" align="center" >
  <tr> 
    <th><h3>Usuario</h3></th>
	<th><h3>Total</h3></th>
    
  </tr >
  
</table>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');


        $sql="SELECT idUsuarios as IdUsuarios, SUM(Valor) as Subtotal FROM separados_abonos
	WHERE $CondicionFecha2
	GROUP BY idUsuarios";
	$sel1=$obVenta->Query($sql);



if($obVenta->NumRows($sel1)){
	$Subtotal=0;
	
	
	
	while($DatosVentas=$obVenta->FetchArray($sel1)){
			
		$SubtotalUser=number_format($DatosVentas["Subtotal"]);
		
		
		$Subtotal=$Subtotal+$DatosVentas["Subtotal"];
		
		$idUser=$DatosVentas["IdUsuarios"];
		
		
		$tbl = <<<EOD

<table border="1"  cellpadding="2" align="center">
 <tr>
  <td>$idUser</td>
  
  <td>$SubtotalUser</td>
 
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	}
	
	
	$Subtotal=number_format($Subtotal);
	
	$tbl = <<<EOD

<table border="1" cellspacing="2" align="center">
 <tr>
  <td align="RIGHT"><h3>SUMATORIA</h3></td>
 
  <td><h3>$Subtotal</h3></td>
  
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	
}



////////////////////////////////////////////////////////
//////////////tabla con los datos de las entregas///////
////////////////////////////////////////////////////////


$tbl = <<<EOD


<BR><BR><span style="color:RED;font-family:'Bookman Old Style';font-size:12px;"><strong><em>Entregas:
</em></strong></span><BR><BR>


<table border="1" cellspacing="2" align="center" >
  <tr> 
	
    <th><h3>Usuario</h3></th>
	<th><h3>Ventas</h3></th>
    <th><h3>Abonos</h3></th>
	
	<th><h3>Egresos</h3></th>
	<th><h3>Entrega</h3></th>
  </tr >
  
</table>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

$sql="SELECT Usuarios_idUsuarios as IdUsuarios, Fecha as Fecha, SUM(Total) as Total "
        . "  FROM $CondicionFacturas AND FormaPago='Contado' GROUP BY Usuarios_idUsuarios";




	$flagQuery=0;
	$TotalVentas=0;
	$TotalAbonos=0;
	$TotalDevoluciones=0;
	$TotalEgresos=0;
	$TotalEntrega=0;
	$Datos=$obVenta->Query($sql);
	
	while($DatosVentas=$obVenta->FetchArray($Datos)){
		$flagQuery=1;	
		$TotalUser=number_format($DatosVentas["Total"]);
		$FechaU=$DatosVentas["Fecha"];
		$idUser=$DatosVentas["IdUsuarios"];
		$TotalVentas=$TotalVentas+$DatosVentas["Total"];
		
		//////////////////////Consulto abonos del usuario
		$sql="SELECT SUM(Valor) as TotalAbonos FROM separados_abonos
		WHERE $CondicionFecha2 AND idUsuarios = '$idUser' ";
		$DatosAbonos=$obVenta->Query($sql);
		$DatosAbonos=$obVenta->FetchArray($DatosAbonos);
		$TotalAbonosUser=number_format($DatosAbonos['TotalAbonos']);
		$TotalAbonos=$TotalAbonos+$DatosAbonos['TotalAbonos'];
		
		
		
		//////////////////////Consulto egresos del usuario
		
		$sql="SELECT SUM(Valor) as TotalEgresos FROM egresos
		WHERE $CondicionFecha2 AND Cuenta LIKE '110510%' AND Usuario_idUsuario = '$idUser' ";
		$DatosEgresos=$obVenta->Query($sql);
        $DatosEgresos=$obVenta->FetchArray($DatosEgresos);
		$TotalEgresosUser=number_format($DatosEgresos['TotalEgresos']);
		$TotalEgresos=$TotalEgresos+$DatosEgresos['TotalEgresos'];
		
		//////////////////////Calculo Entregas
			
		$TotalEntregaUser=$DatosVentas["Total"]+$DatosAbonos['TotalAbonos']-$DatosEgresos['TotalEgresos'];
		
		$TotalEntrega=$TotalEntrega+$TotalEntregaUser;
		$TotalEntregaUser=number_format($TotalEntregaUser);
		
		$tbl = <<<EOD

<table border="1"  cellpadding="2" align="center">
 <tr>
  <td>$idUser</td>
  <td>$TotalUser</td>
  <td>$TotalAbonosUser</td>
  
  <td>$TotalEgresosUser</td>
  <td>$TotalEntregaUser</td>
 
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	}
	
if($flagQuery==1){
	$TotalVentas=number_format($TotalVentas);
	$TotalAbonos=number_format($TotalAbonos);
	
	$TotalEgresos=number_format($TotalEgresos);
	$TotalEntrega=number_format($TotalEntrega);
	
	
	
	$tbl = <<<EOD

<table border="1" cellspacing="2" align="center">
 <tr>
  <td align="RIGHT"><h3>SUMATORIA</h3></td>
 
  <td><h3>$TotalVentas</h3></td>
  <td><h3>$TotalAbonos</h3></td>
  
  <td><h3>$TotalEgresos</h3></td>
  <td><h3>$TotalEntrega</h3></td>
  
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	
}


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

///////////////////////////////////////////////////////
//////////////tabla con los datos de las ventas asignadas a colaboradores//////////////////
////////////////////////////////////////////////////////


$tbl = <<<EOD


<BR><BR><span style="color:RED;font-family:'Bookman Old Style';font-size:12px;"><strong><em>Ventas de Colaboradores:
</em></strong></span><BR><BR>


<table border="1" cellspacing="2" align="center" >
  <tr> 
    <th><h3>Colaborador</h3></th>
	<th><h3>Total</h3></th>
    
  </tr >
  
</table>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');


        $sql="SELECT idColaborador, SUM(Total) as Total FROM colaboradores_ventas
	WHERE $CondicionFecha2
	GROUP BY idColaborador";
	$sel1=$obVenta->Query($sql);



if($obVenta->NumRows($sel1)){
		
	while($DatosVentas=$obVenta->FetchArray($sel1)){
		$DatosColaborador=$obVenta->DevuelveValores("colaboradores", "Identificacion", $DatosVentas["idColaborador"]);	
		$Total=  number_format($DatosVentas["Total"]);
		$tbl = <<<EOD
                
<table border="1"  cellpadding="2" align="center">
 <tr>
  <td>$DatosColaborador[Nombre]</td>
  
  <td>$Total</td>
 
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
	}
	
	
	
}


 
 
//$pdf->writeHTML($tab, false, false, false, false, '');
//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>