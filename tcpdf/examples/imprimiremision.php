<?php

include("../../modelo/php_conexion.php");
////////////////////////////////////////////
/////////////Verifico que haya una sesion activa
////////////////////////////////////////////

////////////////////////////////////////////
/////////////Obtengo el ID de la cotizacion a que se imprimirá 
////////////////////////////////////////////

$obVenta=new conexion(1);
$idRemision = $obVenta->normalizar($_REQUEST["ImgPrintRemi"]);

$idFormatoCalidad=7;

$Documento="<strong>REMISION No. $idRemision</strong>";
require_once('Encabezado.php');
////////////////////////////////////////////
/////////////Obtengo valores de la Remision
////////////////////////////////////////////
			

$DatosRemision=$obVenta->DevuelveValores("remisiones","ID",$idRemision);
$fecha=$DatosRemision["Fecha"];
$observaciones=$DatosRemision["ObservacionesRemision"];
$Clientes_idClientes=$DatosRemision["Clientes_idClientes"];
$Usuarios_idUsuarios=$DatosRemision["Usuarios_idUsuarios"];
////////////////////////////////////////////
/////////////Obtengo valores del centro de costos
////////////////////////////////////////////
		
$DatosCentroCostos=$obVenta->DevuelveValores("centrocosto","ID",$DatosRemision["CentroCosto"]);
////////////////////////////////////////////
/////////////Obtengo datos del cliente
////////////////////////////////////////////
		  		  
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
		  
$nombre_file="Remision_".$fecha."_".$DatosCliente["RazonSocial"];
		  
$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="2" align="left">
    <tr nobr="true">
        <th><strong>Cliente:</strong> $DatosCliente[RazonSocial]</th>
        <th><strong>Proyecto:</strong> $DatosRemision[Obra]</th>
    </tr>
    <tr nobr="true">
        <th><strong>Dirección:</strong> $DatosCliente[Direccion]</th>
        <th><strong>Dirección:</strong> $DatosRemision[Direccion] $DatosRemision[Ciudad]</th>
    </tr>
    <tr nobr="true">
        <th><strong>Teléfono:</strong> NA</th>  <!-- $DatosCliente[Telefono] -->
        <th><strong>Teléfono:</strong> $DatosRemision[Telefono]</th>
    </tr>
    <tr nobr="true">
        <th><strong>Ciudad:</strong> $DatosCliente[Ciudad]</th>
        <th><strong>Retiró:</strong> $DatosRemision[Retira]</th>
    </tr> 
    <tr nobr="true">
        <th><strong>NIT:</strong> $DatosCliente[Num_Identificacion]</th>
        <th><strong>Fecha y Hora de Despacho:</strong> $DatosRemision[FechaDespacho] $DatosRemision[HoraDespacho]</th>
    </tr>
</table>
        
<br>
<hr id="Line3" style="margin:0;padding:0;position:absolute;left:0px;top:219px;width:625px;height:2px;z-index:9;">
  
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');
////////////////////Datos de los items
$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="2" align="center">
  
 <tr nobr="true">
  <th><h3>Ref</h3></th>
  <th colspan="2"><h3>Descripción</h3></th>
  <!-- <th><h3>Valor Unitario</h3></th> -->
  <th><h3>Cantidad</h3></th>
  <!-- <th><h3>Dias</h3></th>
  <th><h3>Total</h3></th> -->
  <th><h3>Peso</h3></th>      
  <th><h3>F. Dev.</h3></th>
  <th><h3>C. Dev.</h3></th>      
 </tr>
 </table>
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');
////////////////////////////////////////////////////////
$PesoTotal=$obVenta->CalculePesoRemision($DatosRemision['Cotizaciones_idCotizaciones']); 
$Consulta=$obVenta->ConsultarTabla("cot_itemscotizaciones","WHERE NumCotizacion='$DatosRemision[Cotizaciones_idCotizaciones]'");
$Subtotal=0;
$IVA=0;
$Total=0;
  $h=0;
  
while($registros2=$obVenta->FetchArray($Consulta)){
		 
$Subtotal=$Subtotal+($registros2["Subtotal"]);
$IVA=$IVA+($registros2["IVA"]);
$Total=$Total+($registros2["Total"]);
$registros2["Total"]=number_format($registros2["Total"]);
$registros2["Subtotal"]=number_format(round($registros2["Subtotal"]));	
$registros2["ValorUnitario"]=number_format(round($registros2["ValorUnitario"]));	

if($h==0){
        $Back="#f2f2f2";
        $h=1;
    }else{
        $Back="white";
        $h=0;
    }
    $PesoItem="";
    if($registros2["TablaOrigen"]=="productosalquiler"){
        $DatosProducto=$obVenta->DevuelveValores("productosalquiler", "Referencia",$registros2["Referencia"] );
        $PesoItem=$DatosProducto["PesoUnitario"]*$registros2["Cantidad"];
    }
    
			
$tbl = <<<EOD
<table border="0" cellpadding="2" cellspacing="2" align="center">
 <tr nobr="true">
  <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$registros2[Referencia]</td>
  <td colspan="2" style="border-bottom: 1px solid #ddd;background-color: $Back;">$registros2[Descripcion]</td>
  <!-- <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$$registros2[ValorUnitario]</td> -->
  <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$registros2[Cantidad]</td>
  <!-- <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$registros2[Multiplicador]</td>
   <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$$registros2[Subtotal]</td> -->
    <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$PesoItem Kg</td>    
    <td style="border-bottom: 1px solid #ddd;background-color: $Back;"> </td>
    <td style="border-bottom: 1px solid #ddd;background-color: $Back;"> </td>
 </tr>
 </table>
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');
			
		  
}
$Subtotal=  number_format($Subtotal);
$IVA=  number_format($IVA);
$Total=  number_format($Total);
$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="2" align="center">
 <tr nobr="true">
  <td colspan="5" align="center"><h3>Observaciones</h3></td></tr>
  <tr nobr="true">
  <td colspan="5" align="left">Cotizacion No.: $DatosRemision[Cotizaciones_idCotizaciones], Peso Total de la
      Remision: $PesoTotal Kgs, Observaciones adicionales: $observaciones</td></tr>
	  <!--   Se elimina la totalizacion de la remision a peticion del cliente
  <tr nobr="true">
  <td colspan="4" align="rigth"><h3>SubTotal</h3></td><td>$$Subtotal</td></tr>
  <tr nobr="true"><td colspan="4" align="rigth"><h3>IVA</h3></td><td>$$IVA</td></tr>
  <tr nobr="true"><td colspan="4" align="rigth"><h3>Total</h3></td><td>$$Total</td>
 </tr>
 -->
 </table>
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');		
		
$tbl = <<<EOD
</br>
 
  <div id="wb_Text6" style="position:absolute;left:35px;top:150px;width:242px;height:18px;z-index:8;text-align:rigth;">
<span style="font-family:'Bookman Old Style';font-size:10px;"><strong><em>Realizado por: $DatosUsuario[Nombre] $DatosUsuario[Apellido]
</em></strong></span></div>
<div id="wb_Text6" style="position:absolute;left:35px;top:150px;width:242px;height:18px;z-index:8;text-align:left;">
<span style="font-family:'Bookman Old Style';font-size:10px;">$PiePagina
</span></div><br><br>

<div id="Div_Firmas" style="text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:8px; text-align:left;">Recibe : ______________________________</span>
<span style="color:#000000;font-family:'Bookman Old Style';font-size:8px; text-align:right;"> Entrega : ______________________________ Fecha:________</span></div>
<br> <br> <br>
<span style="color:#000000;font-family:'Bookman Old Style';font-size:8px; text-align:left;">Devuelve : ______________________________</span>
<span style="color:#000000;font-family:'Bookman Old Style';font-size:8px; text-align:right;"> Recibe : ______________________________ Fecha:________</span></div>

        
<div id="wb_Text6" style="position:absolute;left:35px;top:150px;width:242px;height:18px;z-index:8;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:8px;">Remision Generada por SOFTCONTECH V5.0, Software Diseñado por TECHNO SOLUCIONES SAS, 317 774 0609, info@technosoluciones.com</div></span>
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');		
//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');
//============================================================+
// END OF FILE
//============================================================+
?>