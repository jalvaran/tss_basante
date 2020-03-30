<?php

//require_once('../../librerias/numerosletras.php');
include("../../modelo/php_conexion.php");

////////////////////////////////////////////
/////////////Obtengo el ID de la cotizacion a que se imprimirá 
////////////////////////////////////////////
$idFactura = $_REQUEST["ImgPrintFactura"];
$TipoFactura="ORIGINAL";
if(isset($_REQUEST["TipoFactura"])){
    $TipoFactura=$_REQUEST["TipoFactura"];
}
////////////////////////////////////////////
/////////////Obtengo valores de la Remision
////////////////////////////////////////////
//$IDCoti = $_REQUEST["ImgPrintCoti"];

$obVenta=new conexion(1);
$DatosFactura=$obVenta->DevuelveValores("facturas","idFacturas",$idFactura);
$Fecha=$DatosFactura["Fecha"];
$Hora=$DatosFactura["Hora"];
$observaciones=$DatosFactura["ObservacionesFact"];
$Clientes_idClientes=$DatosFactura["Clientes_idClientes"];
$Usuarios_idUsuarios=$DatosFactura["Usuarios_idUsuarios"];
$idResolucion=$DatosFactura["idResolucion"];

$DatosResolucion=$obVenta->DevuelveValores("empresapro_resoluciones_facturacion","ID",$idResolucion);
////////////////////////////////////////////
/////////////Obtengo datos del cliente y centro de costos
////////////////////////////////////////////
$DatosCentroCostos=$obVenta->DevuelveValores("centrocosto","ID",$DatosFactura["CentroCosto"]);

$DatosCliente=$obVenta->DevuelveValores("clientes","idClientes",$Clientes_idClientes);
////////////////////////////////////////////
/////////////Obtengo datos del Usuario creador y de la empresa propietaria
////////////////////////////////////////////
$DatosUsuario=$obVenta->DevuelveValores("usuarios","idUsuarios",$Usuarios_idUsuarios);
$nombreUsuario=$DatosUsuario["Nombre"];
$ApellidoUsuario=$DatosUsuario["Apellido"];
$DatosEmpresaPro=$obVenta->DevuelveValores("empresapro","idEmpresaPro",$DatosCentroCostos["EmpresaPro"]);
$RazonSocialEP=$DatosEmpresaPro["RazonSocial"];
$DireccionEP=$DatosEmpresaPro["Direccion"];
$TelefonoEP=$DatosEmpresaPro["Celular"];
$CiudadEP=$DatosEmpresaPro["Ciudad"];
$NITEP=$DatosEmpresaPro["NIT"];
$CodigoFactura=$DatosFactura["Prefijo"]." - ".$DatosFactura["NumeroFactura"];		  
$nombre_file="Factura_".$DatosFactura["Fecha"]."_".$DatosCliente["RazonSocial"];
		   
$idFormatoCalidad=2;

$Documento="<strong>FACTURA DE VENTA No. $CodigoFactura<BR>$TipoFactura</strong>";
require_once('Encabezado.php');

////Datos del Cliente
////
////
//$pdf->writeHTML("<br>", true, false, false, false, '');
$tbl = <<<EOD
<table cellspacing="1" cellpadding="2" border="1">
    <tr>
        <td><strong>Cliente:</strong></td>
        <td colspan="3">$DatosCliente[RazonSocial]</td>
        
    </tr>
    <tr>
    	<td><strong>NIT:</strong></td>
        <td colspan="3">$DatosCliente[Num_Identificacion] - $DatosCliente[DV]</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Dirección:</strong></td>
        <td><strong>Ciudad:</strong></td>
        <td><strong>Teléfono:</strong></td>
    </tr>
    <tr>
        <td colspan="2">$DatosCliente[Direccion]</td>
        <td>$DatosCliente[Ciudad]</td>
        <td>$DatosCliente[Telefono]</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Fecha de Facturación:</strong></td>
        <td colspan="2"><strong>Hora:</strong></td>
    </tr>
    <tr>
        <td colspan="2">$DatosFactura[Fecha]</td>
        <td colspan="2">$DatosFactura[Hora]</td>
        
    </tr>
</table>
        
EOD;


$pdf->MultiCell(90, 25, $tbl, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');


////Informacion legal y resolucion DIAN
////
////

$tbl = <<<EOD
<table cellspacing="1" cellpadding="2" border="1">
    <tr>
        <td height="53" align="center" >$DatosEmpresaPro[ResolucionDian], RES DIAN: $DatosResolucion[NumResolucion] del $DatosResolucion[Fecha]
             FACTURA AUT. $DatosResolucion[Prefijo]-$DatosResolucion[Desde] A $DatosResolucion[Prefijo]-$DatosResolucion[Hasta] Autoriza impresion en: $DatosResolucion[Factura]</td> 
    </tr>
     
</table>
<table cellspacing="1" cellpadding="2" border="1">
    <tr>
        <td align="center" ><strong>Vendedor</strong></td>
        <td align="center" ><strong>Forma de Pago</strong></td>
    </tr>
    <tr>
        <td align="center" >$nombreUsuario $ApellidoUsuario</td>
        <td align="center" >$DatosFactura[FormaPago]</td>
    </tr>
     
</table>
<br>  <br><br><br>      
EOD;

$pdf->MultiCell(90, 25, $tbl, 0, 'R', 1, 0, '', '', true,0, true, true, 10, 'M');


////Descripcion de los Items Facturados
////
////
//$SumaDias=$obVenta->SumeColumna("facturas_items", "Dias", "idFactura", $idFactura);
$DatosGeneracion=$obVenta->DevuelveValores("facturas_items", "idFactura", $idFactura);
$Generado=$DatosGeneracion["GeneradoDesde"];
if($Generado=="rem_devoluciones"){
    require_once('factura_items_f1.php');   ///Formato de factura don cantidades y dias
}
if($Generado=="cotizacionesv5"){
    require_once('factura_items_cotizacion.php');   ///Formato de factura don cantidades y dias
}

////Totales de la factura
////
////

$SubtotalFactura=number_format($obVenta->SumeColumna("facturas_items", "SubtotalItem", "idFactura", $idFactura));
$IVAFactura=number_format($obVenta->SumeColumna("facturas_items", "IVAItem", "idFactura", $idFactura));
$TotalFactura=number_format($obVenta->SumeColumna("facturas_items", "TotalItem", "idFactura", $idFactura));
$NumPages=$pdf->getNumPages();

//$TotalLetras=numtoletras($TotalFactura, "PESOS COLOMBIANOS");
if($NumPages<>1){
   
    for($i=1;$i<=$NumPages;$i++){
        $pdf->AddPage();
    }  
}
$tbl = <<<EOD
        
<table cellspacing="1" cellpadding="2" border="1">
    <tr>
        <td height="25" colspan="4">Observaciones: $DatosFactura[ObservacionesFact]</td> 
        
        <td align="rigth"><h3>SUBTOTAL:</h3></td>
        <td align="rigth"><h3>$ $SubtotalFactura</h3></td>
    </tr>
    <tr>
        <td colspan="4" height="25">$DatosEmpresaPro[ObservacionesLegales]</td> 
        <td align="rigth"><h3>IVA:</h3></td>
        <td align="rigth"><h3>$ $IVAFactura</h3></td>
    </tr>
    <tr>
        <td colspan="2" height="50" align="center"><br/><br/><br/><br/><br/>Firma Autorizada</td> 
        <td colspan="2" height="50" align="center"><br/><br/><br/><br/><br/>Firma Recibido</td> 
        <td align="rigth"><h3>TOTAL:</h3></td>
        <td align="rigth"><h3>$ $TotalFactura</h3></td>
    </tr>
     
</table>

        
EOD;

$pdf->MultiCell(180, 30, $tbl, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');



if($Generado=="rem_devoluciones"){
    require_once('factura_items_f2.php');    ///Hoja con la descripcion de los dias facturados
}


//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');

$DatosImpresora=$obVenta->DevuelveValores("config_puertos", "ID", 1);
if($DatosImpresora["Habilitado"]=="SI"){
    $obVenta->ImprimeFacturaPOS($idFactura,$DatosImpresora["Puerto"],1);
}
//============================================================+
// END OF FILE
//============================================================+
?>