<?php

include("../../modelo/php_conexion.php");

////////////////////////////////////////////
/////////////Obtengo el ID de la cotizacion a que se imprimirá 
////////////////////////////////////////////
$idDevolucion = $_REQUEST["ImgPrintDevolucion"];
$idFormatoCalidad=9;

$Documento="<strong>COMPROBANTE DE AJUSTE No. $idDevolucion</strong>";
require_once('Encabezado.php');

////////////////////////////////////////////
/////////////Obtengo valores de la Remision
////////////////////////////////////////////
			
$obVenta=new conexion(1);
$DatosDevolucion=$obVenta->DevuelveValores("rem_devoluciones_totalizadas","ID",$idDevolucion);
$Fecha=$DatosDevolucion["FechaDevolucion"];
$Hora=$DatosDevolucion["HoraDevolucion"];
$observaciones=$DatosDevolucion["ObservacionesDevolucion"];
$Clientes_idClientes=$DatosDevolucion["Clientes_idClientes"];
$Usuarios_idUsuarios=$DatosDevolucion["Usuarios_idUsuarios"];

$DatosRemision=$obVenta->DevuelveValores("remisiones","ID",$DatosDevolucion["idRemision"]);

$DatosFactura=$obVenta->DevuelveValores("Facturas","idFacturas",$DatosDevolucion["Facturas_idFacturas"]);
////////////////////////////////////////////
/////////////Obtengo datos del cliente
////////////////////////////////////////////
$DatosCentroCostos=$obVenta->DevuelveValores("centrocosto","ID",$DatosRemision["CentroCosto"]);

$DatosCliente=$obVenta->DevuelveValores("clientes","idClientes",$Clientes_idClientes);
////////////////////////////////////////////
/////////////Obtengo datos del Usuario creador y de la empresa propietaria
////////////////////////////////////////////
$DatosUsuario=$obVenta->DevuelveValores("usuarios","idUsuarios",$Usuarios_idUsuarios);
$nombreUsuario=$DatosUsuario["Nombre"];
$ApellidoUsuario=$DatosUsuario["Apellido"];
$registros2=$obVenta->DevuelveValores("empresapro","idEmpresaPro",$DatosCentroCostos["EmpresaPro"]);
$RazonSocialEP=$registros2["RazonSocial"];
$DireccionEP=$registros2["Direccion"];
$TelefonoEP=$registros2["Celular"];
$CiudadEP=$registros2["Ciudad"];
$NITEP=$registros2["NIT"];
		  
$nombre_file="ComproAjuste_".$idDevolucion.$Fecha."_".$DatosCliente["RazonSocial"];
		   

$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="2" align="left">
    <tr nobr="true">
        <th><strong>Cliente:</strong> $DatosCliente[RazonSocial]</th>
        <th><strong>Nombre Obra:</strong> $DatosRemision[Obra]</th>
    </tr>
    <tr nobr="true">
        <th><strong>Dirección:</strong> $DatosCliente[Direccion]</th>
        <th><strong>Dirección:</strong> $DatosRemision[Direccion] $DatosRemision[Ciudad]</th>
    </tr>
    <tr nobr="true">
        <th><strong>Teléfono:</strong> $DatosCliente[Telefono]</th>
        <th><strong>Remision:</strong> $DatosRemision[ID]</th>
    </tr>
    <tr nobr="true">
        <th><strong>Ciudad:</strong> $DatosCliente[Ciudad]</th>
        <th><strong>Factura:</strong> $DatosFactura[Prefijo] - $DatosFactura[NumeroFactura]</th>
    </tr> 
    <tr nobr="true">
        <th><strong>NIT:</strong> $DatosCliente[Num_Identificacion]</th>
        <th><strong>Fecha y Hora de Devolucion:</strong> $DatosDevolucion[FechaDevolucion] $DatosDevolucion[HoraDevolucion]</th>
    </tr>
</table>
        
<br>
<hr id="Line3" style="margin:0;padding:0;position:absolute;left:0px;top:219px;width:625px;height:2px;z-index:9;">
  
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');
////////////////////Datos de los items
$tbl = <<<EOD
<table border="0" cellpadding="2" cellspacing="2" align="center">
  
 <tr nobr="true">
  <th><h3>Ref</h3></th>
  <th colspan="3"><h3>Descripción</h3></th>
  <th><h3>Cantidad</h3></th>
  <th><h3>Valor Unitario</h3></th>
 <!-- <th><h3>Subtotal</h3></th>
  <th><h3>Dias</h3></th>
  <th><h3>Total</h3></th> -->
 </tr>
 </table>
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');
////////////////////////////////////////////////////////

$sql="SELECT rd.Total,rd.Subtotal,rd.Dias,rd.ValorUnitario,rd.Cantidad,ci.Referencia,ci.Descripcion"
        . ", rd.idItemCotizacion , rd.idRemision "
        . "FROM rem_devoluciones rd INNER JOIN cot_itemscotizaciones ci ON "
        . "rd.idItemCotizacion=ci.ID WHERE rd.NumDevolucion='$idDevolucion'";
$Consulta=$obVenta->Query($sql);
$GranTotal=0;
$h=0;        
while($registros2=$obVenta->FetchArray($Consulta)){

    if($h==0){
        $Back="#f2f2f2";
        $h=1;
    }else{
        $Back="white";
        $h=0;
    }
    
$GranTotal=$GranTotal+$registros2["Total"];
$registros2["Total"]=number_format($registros2["Total"]);
$registros2["Subtotal"]=number_format($registros2["Subtotal"]);	
$registros2["ValorUnitario"]=number_format(round($registros2["ValorUnitario"]));	
			
$tbl = <<<EOD
<table border="0" cellpadding="2" cellspacing="2" align="center">
         
 <tr nobr="true">
  <th style="border-bottom: 1px solid #ddd;background-color: $Back;">$registros2[Referencia]</th>
  <th colspan="3" style="border-bottom: 1px solid #ddd;background-color: $Back;">$registros2[Descripcion]</th>
  <th style="border-bottom: 1px solid #ddd;background-color: $Back;">$registros2[Cantidad]</th>
  <th style="border-bottom: 1px solid #ddd;background-color: $Back;">$$registros2[ValorUnitario]</th>
  <!-- <th style="border-bottom: 1px solid #ddd;background-color: $Back;">$$registros2[Subtotal]</th>
  <th style="border-bottom: 1px solid #ddd;background-color: $Back;">$registros2[Dias]</th>
  <th style="border-bottom: 1px solid #ddd;background-color: $Back;">$$registros2[Total]</th> -->
 </tr>
 </table>
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');
			
		  
}

//////////////Se dibuja el total de la devolcion
///
///
///
$GranTotal=  number_format($GranTotal);
$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="0" align="center">
 <tr nobr="true">
  <td colspan="5" align="center"><h3>Observaciones</h3></td></tr>
  <tr nobr="true">
  <td colspan="5" align="left">$observaciones</td></tr>
  <!-- <tr nobr="true">
  <td colspan="4" align="rigth"><h3>Esta Devolucion: </h3></td><td>$$GranTotal</td> 
  
 </tr> -->
 </table>
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');		

//////////////////Se dibujan los faltantes
////
////


////////////////////Datos de los items
$tbl = <<<EOD
<br><H3>FALTANTES:</H3><br>
   <table border="0" cellpadding="2" cellspacing="2" align="center">
  
 <tr nobr="true">
  <th><h3>Ref</h3></th>
  <th colspan="3"><h3>Descripción</h3></th>
  <th><h3>Cantidad Entregada</h3></th>
  <th><h3>Ajustes</h3></th>
  <th><h3>Faltantes</h3></th>
  
 </tr>
 </table>
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');
////////////////////////////////////////////////////////

$sql="SELECT rr.CantidadEntregada,rr.idItemCotizacion,rr.idRemision, ci.Referencia,ci.Descripcion"
        . " FROM rem_relaciones rr "
        . "INNER JOIN cot_itemscotizaciones ci  "
        . "ON rr.idItemCotizacion=ci.ID"
        . " WHERE rr.idRemision='$DatosRemision[ID]'";
$Consulta=$obVenta->Query($sql);
 $BanderaFaltantes=0; 
 $h=0;
while($DatosItemRemision=$obVenta->FetchArray($Consulta)){

    if($h==0){
        $Back="#f2f2f2";
        $h=1;
    }else{
        $Back="white";
        $h=0;
    }
//$Entregas=$obVenta->Sume('rem_relaciones', "CantidadEntregada", " WHERE idItemCotizacion='$registros2[idItemCotizacion]' AND idRemision='$registros2[idRemision]'");
$Devoluciones=$obVenta->Sume("rem_devoluciones", "Cantidad", " WHERE idItemCotizacion='$DatosItemRemision[idItemCotizacion]' AND idRemision='$DatosRemision[ID]'");
$Faltantes=$DatosItemRemision["CantidadEntregada"]-$Devoluciones;
$BanderaFaltantes=$BanderaFaltantes+$Faltantes;
if($Faltantes<>0){    
$tbl = <<<EOD
<table border="0" cellpadding="2" cellspacing="2" align="center">
         
 <tr nobr="true">
  <th style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemRemision[Referencia]</th>
  <th colspan="3" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemRemision[Descripcion]</th>
  <th style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemRemision[CantidadEntregada]</th>
  <th style="border-bottom: 1px solid #ddd;background-color: $Back;">$Devoluciones</th>
  <th style="border-bottom: 1px solid #ddd;background-color: $Back;">$Faltantes</th>
  
 </tr>
 </table>
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');
  
}
    
		  
}
if($BanderaFaltantes==0){
    $pdf->writeHTML("<br><h3>No se encontraron faltantes</h3><br>", false, false, false, false, '');  
}
/////////////////////////////////////////Se dibija el mensaje final
/////
////
////
$tbl = <<<EOD
</br>
 
  <div id="wb_Text6" style="position:absolute;left:35px;top:150px;width:242px;height:18px;z-index:8;text-align:rigth;">
<span style="font-family:'Bookman Old Style';font-size:10px;"><strong><em>Realizado por: $DatosUsuario[Nombre] $DatosUsuario[Apellido]
</em></strong></span></div>
<div id="wb_Text6" style="position:absolute;left:35px;top:150px;width:242px;height:18px;z-index:8;text-align:left;">
<span style="font-family:'Bookman Old Style';font-size:10px;">$PiePagina
</span></div><br><br>

<div id="Div_Firmas" style="text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:8px; text-align:left;">Responsable: ______________________________</span>
<span style="color:#000000;font-family:'Bookman Old Style';font-size:8px; text-align:right;"> Almacen: ______________________________</span></div>

        
<div id="wb_Text6" style="position:absolute;left:35px;top:150px;width:242px;height:18px;z-index:8;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:8px;">Documento Generado por SOFTCONTECH V5.0, Software Diseñado por TECHNO SOLUCIONES SAS, 317 774 0609, info@technosoluciones.com</div></span>
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');		
//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');
//============================================================+
// END OF FILE
//============================================================+
?>