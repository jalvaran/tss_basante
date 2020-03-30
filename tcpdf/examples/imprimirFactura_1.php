<?php
require_once('tcpdf_include.php');
//require_once('../../librerias/numerosletras.php');
include("../../modelo/php_conexion.php");
////////////////////////////////////////////
/////////////Verifico que haya una sesion activa
////////////////////////////////////////////
session_start();
if(!isset($_SESSION["username"]))
   header("Location: ../../index.php");
////////////////////////////////////////////
/////////////Obtengo el ID de la cotizacion a que se imprimirá 
////////////////////////////////////////////
$idFactura = $_REQUEST["ImgPrintFactura"];
////////////////////////////////////////////
/////////////Obtengo valores de la Remision
////////////////////////////////////////////
$IDCoti = $_REQUEST["ImgPrintCoti"];
$idFormatoCalidad=1;

$Documento="<strong>COTIZACION No. $IDCoti</strong>";
require_once('Encabezado.php');

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
		  
$nombre_file="Factura_".$DatosFactura["Fecha"]."_".$DatosCliente["RazonSocial"];
		   
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Techno Soluciones');
$pdf->SetTitle('Facturas TS');
$pdf->SetSubject('Facturas');
$pdf->SetKeywords('Techno Soluciones, PDF, Facturas, CCTV, Alarmas, Computadores, Software');
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, 60, PDF_HEADER_TITLE.'', "");

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10, 35, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(10);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/spa.php')) {
	require_once(dirname(__FILE__).'/lang/spa.php');
	$pdf->setLanguageArray($l);
}
// ---------------------------------------------------------
// set font
//$pdf->SetFont('helvetica', 'B', 6);
// add a page
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 7);
///////////////////////////////////////////////////////
//////////////encabezado//////////////////
////////////////////////////////////////////////////////
//////
//////
$pdf->SetFillColor(255, 255, 255);

$txt="<h3>".$DatosEmpresaPro["RazonSocial"]."<br>NIT ".$DatosEmpresaPro["NIT"]."</h3>";
$pdf->MultiCell(60, 5, $txt, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');
$txt=$DatosEmpresaPro["Direccion"]."<br>".$DatosEmpresaPro["Telefono"]."<br>".$DatosEmpresaPro["Ciudad"]."<br>".$DatosEmpresaPro["WEB"];
$pdf->MultiCell(60, 5, $txt, 0, 'C', 1, 0, '', '', true,0, true, true, 10, 'M');
$txt="<h3>FACTURA DE VENTA ".$DatosFactura["Prefijo"]." - ".$DatosFactura["NumeroFactura"]."<h3>";
$txt.="<br><h5>Impreso por SOFTCONTECH, Techno Soluciones SAS <BR>NIT 900.833.180 3177740609</h5>";
$pdf->MultiCell(60, 5, $txt, 0, 'R', 1, 0, '', '', true,0, true ,true, 10, 'M');

////Datos del Cliente
////
////
$pdf->writeHTML("<br><br><br>", true, false, false, false, '');
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
//$TotalLetras=numtoletras($TotalFactura, "PESOS COLOMBIANOS");


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
//============================================================+
// END OF FILE
//============================================================+
?>